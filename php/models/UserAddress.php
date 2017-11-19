<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/19
 * Time: 20:39
 */

namespace app\models;


use app\base\ActiveRecord;

class UserAddress extends ActiveRecord
{

    public function rules()
    {
        return [
            [['user_id'], 'required', 'message' => '数据不能为空'],
        ];
    }

}