<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/16
 * Time: 21:52
 */

namespace app\base\api;


class Controller extends \app\base\Controller
{

    public function init()
    {
        $content = file_get_contents('php://input');
        $_POST = json_decode($content, true);
        parent::init();
    }

}