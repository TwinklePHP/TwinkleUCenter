<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2017/11/16
 * Time: 10:46
 */

namespace app\models;

use app\base\ActiveRecord;
use Yii;

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
            [['email'], 'unique', 'message' => '邮箱已注册']
        ];
    }

    public function getUser($params = [])
    {
        if (empty($params)) {
            return false;
        }
        $params['limit'] = 1;
        $userList = $this->getList($params);
        return empty($userList) ? false : $userList[0];
    }

    public function fromUserAttributes()
    {
        $attributes = $this->attributes();

        foreach ($attributes as $key => $attr) {
            if (in_array($attr, ['salt', 'create_time', 'is_validated'])) {
                unset($attributes[$key]);
            }
        }

        return array_values($attributes);
    }


    public function beforeSave($insert)
    {
        if ($insert) {
            $this->salt = md5(rand());
            $this->password = md5($this->password . $this->salt . Yii::$app->params['passToken']);
            $this->create_time = time();
        }
        return parent::beforeSave($insert);
    }

    public function afterInsert()
    {
        // 将注册信息写入redis，用来发送注册邮件

    }
}