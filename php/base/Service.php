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

    public function __construct()
    {

    }

    /**
     * 格式化输出
     *
     * @param mixed $data
     * @return array
     */
    protected function format($data = null)
    {
        $result = [];
        if (is_array($data)) {
            $result = $data;
        } elseif (is_bool($data) || is_int($data)) {
            $result['status'] = empty($data) ? 1 : 0;
            $result['msg'] = empty($data) ? 'fail' : 'success';
        } elseif (is_string($data)) {
            $result['data'] = $data;
        } elseif (is_object($data)) {
            $result = (array)$data;
        }

        return array_merge($this->result, $result);
    }
}