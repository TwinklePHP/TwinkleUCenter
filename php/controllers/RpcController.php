<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 21:25
 */

namespace app\controllers;

use Yii;
use app\helpers\Str;
use twinkle\service\Api;
use app\base\rpc\Controller;

class RpcController extends Controller
{

    public function actionIndex()
    {
        $serviceName = Yii::$app->request->get('name','NotFound');
        $class = '\\app\\services\\' . Str::ucWords($serviceName).'Service';
        new Api([
            'type' => 'rpc',
            'driver' => 'Yar',
            'object' => new $class,
        ]);
    }

}