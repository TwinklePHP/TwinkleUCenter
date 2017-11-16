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
     * 通过ID查用户信息
     *
     * @param $userId
     * @return array
     */
    public function getUserInfoById($userId)
    {
        return $this->format(['user_id' => $userId]);
    }

}