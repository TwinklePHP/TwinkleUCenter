<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2017/11/16
 * Time: 11:34
 */

namespace app\models;


use app\base\ActiveRecord;

class UserExtend extends ActiveRecord
{

    public function rules()
    {
        return [
            [['user_id'], 'required', 'message' => '数据不能为空'],
            [['user_id'],'unique','message'=>'用户ID必须唯一']
        ];
    }

}