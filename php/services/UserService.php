<?php

/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 21:31
 */
/**
 * 用户服务
 */

namespace app\services;

use app\base\Service;
use app\models\UserExtend;
use app\models\UserInfo;
use app\models\UserLoginLog;
use app\models\UserOpen;
use Yii;
use yii\db\Expression;

class UserService extends Service
{

    /**
     * 注册接口
     *
     * @param array $data
     * @return array
     */
    public function register($data)
    {
        if (empty($data['email'])) {
            return $this->fail(Yii::t('user', 'email_can_not_empty'));
        }
        if (empty($data['first_name'])) {
            return $this->fail('用户名称不能为空');
        }
        if (empty($data['password'])) {
            return $this->fail('密码不能为空');
        }
        if (empty($data['repassword'])) {
            return $this->fail('确认密码不能为空');
        }
        if ($data['repassword'] != $data['repassword']) {
            return $this->fail('密码不匹配');
        }

        $userInfoModel = new UserInfo();
        if ($checkEmail = $userInfoModel->getUser(['email' => $data['email'], 'select' => 'user_id'])) {
            return $this->fail('邮箱已存在');
        }

        $userExtendModel = new UserExtend();
        $userOpenModel = new UserOpen();
        $userInfoFields = $userInfoModel->fromUserAttributes();
        $userInfoData = [];
        $userExtendFields = $userExtendModel->fromUserAttributes();
        $userExtendData = [];
        $userOpenFields = $userOpenModel->fromUserAttributes();
        $userOpenData = [];

        foreach ($data as $key => $value) {
            if ($key == 'password' && empty($value)) {
                return $this->fail('密码能为空');
            }
            if (in_array($key, $userInfoFields)) {
                $userInfoData[$key] = $value;
            }
            if (in_array($key, $userExtendFields)) {
                $userExtendData[$key] = $value;
            }
            if (in_array($key, $userOpenFields)) {
                $userOpenData[$key] = $value;
            }
        }

        if (!empty($userInfoData)) {
            $userInfoData['lang'] = $this->lang;
            $userInfoData['plat'] = $this->plat;
        }
        
        $trans = Yii::$app->db->beginTransaction();
        try {
            if ($result = $userInfoModel->saveData($userInfoData)) {
                $userExtendData['user_id'] = $userInfoModel->user_id;
                if (!($resultExtend = $userExtendModel->saveData($userExtendData))) {
                    $result = false;
                }
            }

            // 第三方注册判断
            if ($result && !empty($userOpenData)) {
                $userOpenData['user_id'] = $userInfoModel->user_id;
                if (!($resultOpen = $userOpenModel->saveData($userOpenData))) {
                    $result = false;
                }
            }
            if ($result == false) {
                $trans->rollBack();
            } else {
                $trans->commit();
            }
        } catch (\Exception $e) {
            $trans->rollBack();
            return $this->fail($e->getMessage());
        }

        return $this->format($result ? ['user_id' => $userInfoModel->user_id] : false);
    }

    /**
     * 登录接口
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function login($email, $password)
    {
        $userInfoModel = new UserInfo();
        $userInfo = $userInfoModel->getUser(['email' => $email]);
        if (empty($userInfo)) {
            return $this->format(['status' => 1, 'msg' => '邮箱不存在']);
        }
        if ($userInfo['password'] <> md5($password . $userInfo['salt'] . Yii::$app->params['passToken'])) {
            return $this->format(['status' => 1, 'msg' => '密码不正确']);
        }

        //记录登录信息
        $nowTime = time();
        $userExtendModel = new UserExtend();
        $userExtendModel->saveData([
            'user_id' => $userInfo['user_id'],
            'last_login_time' => $nowTime,
            'login_count' => new Expression('login_count+1'),
            'last_login_lang' => $this->lang,
            'last_login_plat' => $this->plat,
        ]);
        (new UserLoginLog())->saveData([
            'user_id' => $userInfo['user_id'],
            'lang' => $this->lang,
            'plat' => $this->plat,
            'create_time' => $nowTime
                ], true);

        unset($userInfo['password'], $userInfo['salt']);
        return $this->format(['user_info' => $userInfo]);
    }

    /**
     * 编辑用户信息
     *
     * @param int|array $condition
     * @param array $data
     * @return array
     */
    public function editUser($condition, $data = [])
    {
        $where = [];
        if (is_numeric($condition)) {
            $where['user_id'] = $condition;
        } elseif(is_array($condition)) {
            $where = $condition;
        } else {
            return $this->fail('参数不合法');
        }
        $userInfoModel = new UserInfo();
        $userInfo = $userInfoModel->getUser($where);
        if (empty($userInfo)) {
            return $this->format(['status' => 1, 'msg' => '无符合条件用户']);
        }

        $userExtendModel = new UserExtend();

        $userInfoFields = $userInfoModel->fromUserAttributes();
        $userInfoData = [];
        $userExtendFields = $userExtendModel->fromUserAttributes();
        $userExtendData = [];
        foreach ($data as $key => $value) {
            if ($key == 'password' && empty($value)) {
                return $this->format(['status' => 1, 'msg' => '密码能为空']);
            }
            if (in_array($key, $userInfoFields)) {
                $userInfoData[$key] = $value;
            }
            if (in_array($key, $userExtendFields)) {
                $userExtendData[$key] = $value;
            }
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            $result = true;
            if (!empty($userInfoData)) {
                $userInfoData['user_id'] = $userInfo['user_id'];
                if (isset($userInfoData['password'])) {
                    $userInfoData['salt'] = md5(rand());
                    $userInfoData['password'] = md5($userInfoData['password'] . $userInfoData['salt'] . Yii::$app->params['passToken']);
                }
                $result = $userInfoModel->saveData($userInfoData);
            }

            if (!empty($userExtendData) && $result) {
                $userExtendData['user_id'] = $userInfo['user_id'];
                if (!($resultExtend = $userExtendModel->saveData($userExtendData))) {
                    $result = false;
                }
            }

            if ($result == false) {
                $trans->rollBack();
            } else {
                $trans->commit();
            }
        } catch (\Exception $e) {
            $trans->rollBack();
            return $this->fail($e->getMessage());
        }

        return $this->format($result);
    }

    /**
     * 查询用户信息
     *
     * @param $userId
     * @return array
     */
    public function getUserById($userId)
    {
        if (empty($userId)) {
            return $this->fail('用户ID不能为空');
        }
        $userInfo = (new UserInfo())->getList(['user_id' => $userId]);
        return $this->format($userInfo ? ['user_info' => $userInfo[0]] : false);
    }

    /**
     * 查询用户列表
     *
     * @param array $params 查询条件
     * @param int $page 页码
     * @param int $pageSize 每页条数
     * @return  array
     */
    public function getUserByCondition($params, $page = 1, $pageSize = 20)
    {
        empty($params['orderBy']) && $params['orderBy'] = 'user_id desc';
        $params['offset'] = ($page - 1) * $pageSize;
        $params['pageSize'] = $pageSize;
        $userList = (new UserInfo())->getList($params);
        return $this->format($userList ? ['list' => $userList] : false);
    }

    /**
     * 通过user_id批量查询用户信息
     * @param $userIdArr
     * @return array
     */
    public function getUserByIds($userIdArr)
    {
        $userIdStr = implode(',', $userIdArr);
        $params['orderBy'] = "FIND_IN_SET(user_id,'{$userIdStr}')";
        $params['in'] = [['user_id' => $userIdArr]];
        return $this->getUserByCondition($params, 1, count($userIdArr));
    }

}
