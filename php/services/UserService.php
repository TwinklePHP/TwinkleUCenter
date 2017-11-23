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
     * @param array $extend 额外信息
     * @return array
     */
    public function register($data, $extend = ['lang' => 'en', 'plat' => 1])
    {
        if (empty($data['email'])) {
            return $this->format(['status' => 1, 'msg' => '邮箱不能为空']);
        }
        if (empty($data['password'])) {
            return $this->format(['status' => 1, 'msg' => '密码不能为空']);
        }

        $userInfoModel = new UserInfo();
        if ($checkEmail = $userInfoModel->getUser(['email' => $data['email'], 'select' => 'user_id'])) {
            return $this->format(['status' => 1, 'msg' => '邮箱已存在']);
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
                return $this->format(['status' => 1, 'msg' => '密码能为空']);
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
            $userInfoData['lang'] = isset($extend['lang']) ? $extend['lang'] : 'en';
            $userInfoData['plat'] = isset($extend['plat']) ? $extend['plat'] : 1;
        }

        // ??? 考虑下要不要用事务来处理
        if ($result = $userInfoModel->saveData($userInfoData)) {
            $userExtendData['user_id'] = $userInfoModel->user_id;
            if (!($resultExtend = $userExtendModel->saveData($userExtendData))) {
                $userInfoModel->delete();
                $result = false;
            }
        }

        // 第三方注册判断
        if ($result && !empty($userOpenData)) {
            $userOpenData['user_id'] = $userInfoModel->user_id;
            if (!($resultOpen = $userOpenModel->saveData($userOpenData))) {
                $userInfoModel->delete();
                $userExtendModel->delete();
                $result = false;
            }
        }

        return $this->format($result);
    }

    /**
     * 登录接口
     *
     * @param string $email
     * @param string $password
     * @param array $extend 其他补充信息 ['lang'=>'en','plat'=>1]
     * @return array
     */
    public function login($email, $password, $extend = ['lang' => 'en', 'plat' => 1])
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

        empty($extend['lang']) && $extend['lang'] = 'en';
        empty($extend['plat']) && $extend['plat'] = 1;

        $nowTime = time();
        $userExtendModel = new UserExtend();
        $userExtendModel->saveData([
            'user_id' => $userInfo['user_id'],
            'last_login_time' => $nowTime,
            'login_count' => new Expression('login_count+1'),
            'last_login_lang' => $extend['lang'],
            'last_login_plat' => $extend['plat'],
        ]);
        (new UserLoginLog())->saveData([
            'user_id' => $userInfo['user_id'],
            'lang' => $extend['lang'],
            'plat' => $extend['plat'],
            'create_time' => $nowTime
        ], true);

        unset($userInfo['password'], $userInfo['salt']);
        return $this->format($userInfo);
    }

    /**
     * 编辑单个用户信息
     *
     * @param int|array $condition
     * @param array $data
     * @return array
     */
    public function editUser($condition, $data = [])
    {
        $where = [];
        if (is_int($condition)) {
            $where['user_id'] = $condition;
        } else {
            $where = $condition;
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
                $userInfoModel->saveData($userInfo);
                $result = false;
            }
        }
        return $this->format($result);
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
        return $this->format($userList);
    }

    /**
     * 通过user_id查询用户信息
     * @param $userIdArr
     * @return array
     */
    public function getUserByIds($userIdArr)
    {
        $userIdStr = implode(',', $userIdArr);
        $params['orderBy'] = "FIND_IN_SET(goods_id,'{$userIdStr}')";
        $params['in'] = [['user_id' => $userIdArr]];
        return $this->getUserByCondition($params, 1, count($userIdArr));
    }
}