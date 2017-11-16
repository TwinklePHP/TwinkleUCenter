<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/16
 * Time: 21:30
 */

/**
 * 获取特定token
 */

namespace app\services;

use Yii;
use app\base\Service;

class TokenService extends Service
{

    /**
     * 表单token
     */
    public function form()
    {
        $token = Yii::$app->request->getCsrfToken();
        return $this->format(['token' => $token]);
    }
}