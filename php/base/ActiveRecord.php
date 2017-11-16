<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2017/11/16
 * Time: 10:46
 */

namespace app\base;

use app\helpers\Str;

class ActiveRecord extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return Str::revertUcwords(
            substr(strrchr(get_called_class(), '\\'), 1),
            '_'
        );
    }

    /**
     * 设置查询条件
     *
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected function setWhere($params = [])
    {
        $obj = self::find();

        foreach ($params as $key => $value) {
            switch ($key) {
                case '>':
                case '>=':
                case '<':
                case '<=':
                    foreach ($value as $k => $v) {
                        $obj->andWhere([$key, $k, $v]);
                    }
                    break;
                case 'orderBy':

                    break;
                default:
                    $_key = $key;
                    if (strpos($key, ".") !== false) {
                        $_key = substr(strrchr($key,'.'),1);
                    }
                    $obj->andWhere("$key = :$_key", [":$_key" => $value]);
            }
        }

        return $obj;
    }

}