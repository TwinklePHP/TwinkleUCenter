<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 21:24
 */

namespace app\base;


abstract class Service
{
    public $result = ['status' => 0, 'msg' => 'success'];

    /**
     * 格式化输出
     *
     * @param mixed $data
     * @return array
     */
    protected function format($data = null)
    {
        if (is_bool($data)) {
            $data['status'] = empty($data) ?: 0;
            $data['msg'] = empty($data) ? 'fail' : 'success';
        } elseif (is_string($data)) {
            $data['data'] = $data;
        } elseif (is_object($data)) {
            $data = (array)$data;
        }

        return array_merge($this->result, $data);
    }
}