<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 21:31
 */

namespace app\services;

use app\models\UserLoginLog;
use Yii;
use app\base\Service;
use app\models\UserExtend;
use app\models\UserInfo;
use yii\db\Expression;

class UserService extends Service
{

    /**
     * 注册接口
     *
     * @param array $data
     * @return array
     */
    public function register($data = [])
    {
        if (empty($data['email'])) {
            return $this->format(['status' => 1, 'msg' => '邮箱不能为空']);
        }
        if (empty($data['password'])) {
            return $this->format(['status' => 1, 'msg' => '密码不能为空']);
        }

        $userInfoModel = new UserInfo();
        if ($checkEmail = $userInfoModel->getUser(['email' => $data['email'], 'select' => 'email'])) {
            return $this->format(['status' => 1, 'msg' => '邮箱已存在']);
        }

        if ($result = $userInfoModel->saveData($data)) {
            $data['user_id'] = $userInfoModel->user_id;
            $userExtendModel = new UserExtend();
            if (!($resultExtend = $userExtendModel->saveData($data))) {
                $userInfoModel->delete();
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
        ]);
        (new UserLoginLog())->saveData([
            'user_id' => $userInfo['user_id'],
            'create_time' => $nowTime
        ],true);

        unset($userInfo['password'], $userInfo['salt']);
        return $this->format($userInfo);
    }

    /**
     * 编辑指定用户信息
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

        $userInfoFields = ['password', 'first_name', 'last_name', 'sex', 'msn', 'phone', 'is_validated', 'avatar'];
        $userInfoData = [];
        $userExtendFields = [];
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
        $resultExtend = true;
        if (!empty($userExtendData) && $result) {
            $userExtendData['user_id'] = $userInfo['user_id'];
            $userExtendModel = new UserExtend();
            if (!($resultExtend = $userExtendModel->saveData($userExtendData))) {
                $userInfoModel->saveData($userInfo);
                $result = false;
            }
        }
        return $this->format($result && $resultExtend);
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