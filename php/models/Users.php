<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2017/11/16
 * Time: 10:46
 */

namespace app\base\models;


use app\base\ActiveRecord;

class Users extends ActiveRecord
{

    public function getUser($condition, $field = '*')
    {
        $userList = $this->getUserList($condition, $field);
        return isset($userList[0]) ? $userList[0] : false;
    }

    public function getUserList($condition, $field = '*')
    {

    }

    public function addUser($data = [])
    {
        if (empty($data)) {
            return false;
        }


    }

}