<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 22:28
 */

namespace app\services;


use app\base\Service;

class NotFoundService extends Service
{
    public function error()
    {
        return $this->format(['status' => 1, 'msg' => '请求不合法,请确认service和method是否存在']);
    }

    public function __call($name, $args = [])
    {
        return $this->error($name, $args);
    }
}