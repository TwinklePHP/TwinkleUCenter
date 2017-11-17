<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 21:31
 */

namespace app\services;


use app\base\Service;
use app\models\UserExtend;
use app\models\UserInfo;

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
        $userInfoModel = new UserInfo();
        $result = $userInfoModel->saveData($data);
        if ($result) {
            $data['user_id'] = $userInfoModel->user_id;
            $userExtendModel = new UserExtend();
            if (!$userExtendModel->saveData($data)) {
                $userInfoModel->delete();
                $result = false;
            }
        }
        return $this->format($result);
    }

    /**
     * 登录接口
     *
     * @param $email
     * @param $password
     * @return array
     */
    public function login($email, $password)
    {
        return $this->format(['email' => $email, 'password' => $password]);
    }
}