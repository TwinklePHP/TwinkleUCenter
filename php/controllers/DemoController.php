<?php
/**
 * Created by PhpStorm.
 * User: chengwopei
 * Date: 2017/11/27
 * Time: 18:05
 */

namespace app\controllers;

use Yii;
use app\base\rpc\Controller;

class DemoController extends Controller
{
    
    public function actionIndex()
    {
        return $this->renderPartial('sign');
    }
    
    public function  actionSignIn()
    {
        $username = Yii::$app->request->post('username', '');
        $password = Yii::$app->request->post('password', '');
        
        $client = new \Yar_Client(Yii::$app->request->hostInfo . '/rpc/user');
        
        $result = $client->login($username, $password);
        
        if (isset($result['status']) && $result['status'] === 0) {
            $info = empty($result['user_info']) ? [] : $result['user_info'];
            return $this->renderPartial('sign', [
                'info' => $info,
                'step' => 'login_success',
            ]);
        }
        
        return $this->renderPartial('sign', ['step' => 'login_fail']);
    }
    
}