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

    public static function getKey()
    {
        $keys = static::primaryKey();
        if (count($keys) == 1) {
            return $keys[0];
        }
        return $keys;
    }

    /**
     * 填加或更新数据
     *
     * @param array $data
     * @param bool $insert 是否直接添加
     * @return bool
     */
    public function saveData($data, $insert = false)
    {
        if (!$insert) {
            $primaryKey = static::getKey();

            if (is_string($primaryKey)) {
                !empty($data[$primaryKey]) && $model = self::findOne($data[$primaryKey]);
            } elseif (is_array($primaryKey)) {
                $condition = [];
                $hasKey = true;
                foreach ($primaryKey as $key) {
                    if (!isset($data[$key])) {
                        $hasKey = false;
                        break;
                    }
                    $condition[$key] = $data[$key];
                }
                $hasKey && $model = self::findOne($condition);
            }
        }

        if (empty($model)) {
            $this->isNewRecord = true;
            $this->setAttributes($data, false);
            $result = $this->save();
        } else {
            foreach ($data as $key => $val) {
                $model->{$key} = $val;
            }
            $result = $model->save();
        }
        return $result;
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
                        $_key = substr(strrchr($key, '.'), 1);
                    }
                    $obj->andWhere("$key = :$_key", [":$_key" => $value]);
            }
        }

        return $obj;
    }
}