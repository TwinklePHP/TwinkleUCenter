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

    /**
     * 获取单个用户信息
     *
     * @param array $params
     * @return bool|mixed|\yii\db\ActiveRecord
     */
    public function getUser($params = [])
    {
        if (empty($params)) {
            return false;
        }
        $params['limit'] = 1;
        $params['select'] = 'ue.*,u.email,u.`password`,u.salt,u.first_name,u.last_name,u.sex,u.msn,u.phone,u.is_validated,u.avatar,u.lang,u.plat,u.create_time';
        $userList = $this->getList($params);
        return empty($userList) ? false : $userList[0];
    }


    /**
     * 查询用户列表
     *
     * @param array $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList($params = [])
    {
        $userInfoAttr = $this->attributes();
        $userExtendAttr = (new UserExtend())->attributes();
        foreach ($params as $key => $value) {
            if (in_array($key, $userInfoAttr)) {
                $params["u.{$key}"] = $value;
                unset($params[$key]);
            } elseif (in_array($key, $userExtendAttr)) {
                $params["ue.{$key}"] = $value;
                unset($params[$key]);
            }
        }
        $params['alias'] = 'u';
        $params['left join'] = ['user_extend ue' => 'ue.user_id = u.user_id'];
        empty($params['select']) && $params['select'] = 'ue.*,u.email,u.first_name,u.last_name,u.sex,u.msn,u.phone,u.is_validated,u.avatar,u.lang,u.plat,u.create_time';
        return $this->setWhere($params)->asArray()->all();
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