<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/23
 * Time: 19:34
 */

/**
 * 第三方登录
 */
namespace app\services;


use app\base\Service;
use app\models\UserInfo;
use app\models\UserOpen;

class UserOpenService extends Service
{

    /**
     * Facebook登录
     *
     * @param $fbUid
     * @return array
     */
    public function checkFbUid($fbUid)
    {
        if (empty($fbUid)) {
            return $this->fail('fbUid不能为空');
        }
        $userOpenInfo = (new UserOpen())->getOne(['site_name' => 'facebook', 'open_id' => $fbUid]);
        if (!$userOpenInfo) {
            return $this->format(['msg' => 'facebook账号不存在,请先注册', 'user_info' => false]);
        }

        $userInfo = (new UserInfo())->getUser(['user_id' => $userOpenInfo['user_id']]);
        return $this->format(['user_info' => $userInfo]);
    }

}