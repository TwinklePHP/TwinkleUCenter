<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/23
 * Time: 19:35
 */

namespace app\models;


use app\base\ActiveRecord;

class UserOpen extends ActiveRecord
{

    public function rules()
    {
        return [
            [['user_id', 'site_name', 'open_id'], 'required', 'message' => '数据不能为空'],
            [['site_name', 'open_id'], 'unique', 'targetAttribute' => ['site_name', 'open_id'], 'message' => 'open_id已存在']
        ];
    }

    public function fromUserAttributes()
    {
        return ['site_name','open_id','access_token'];
    }
}