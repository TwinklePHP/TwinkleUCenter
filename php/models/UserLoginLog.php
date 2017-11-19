<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/19
 * Time: 20:27
 */

namespace app\models;

use Yii;
use app\base\ActiveRecord;

/**
 * 登录日志
 *
 * Class UserLoginLog
 * @package app\models
 *
 * @property string $ip 登录IP
 */
class UserLoginLog extends ActiveRecord
{
    public function rules()
    {
        return [
            [['user_id'], 'required', 'message' => '数据不能为空'],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->ip = Yii::$app->ip->get();
        }

        return parent::beforeSave($insert);
    }
}