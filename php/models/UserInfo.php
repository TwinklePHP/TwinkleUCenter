<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2017/11/16
 * Time: 10:46
 */

namespace app\models;

use Yii;
use app\base\ActiveRecord;

/**
 * Class UserInfo
 * @package app\models
 *
 * @property int user_id 用户ID
 * @property string salt 加盐
 * @property string password 密码
 * @property int create_time 注册时间
 */
class UserInfo extends ActiveRecord
{

    public function rules()
    {
        return [
            [['email', 'password', 'first_name'], 'required', 'message' => '数据不能为空'],
            [['email'],'unique','message'=>'邮箱已注册']
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->salt = md5($this->password);
            $this->password = md5($this->salt.Yii::$app->params['passToken']);
            $this->create_time = time();
        }
        return parent::beforeSave($insert);
    }
}