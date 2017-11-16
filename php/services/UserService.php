<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 21:31
 */

namespace app\services;


use app\base\Service;

class UserService extends Service
{

    /**
     * 登录接口
     *
     * @param $email
     * @param $password
     * @return array
     */
    public function login($email, $password = '')
    {
        return $this->format(['email'=>$email]);
    }
}