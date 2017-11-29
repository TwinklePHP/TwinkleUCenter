<?php

/**
 * Created by PhpStorm.
 * User: chengwopei
 * Date: 2017/11/27
 * Time: 18:05
 */

namespace app\controllers;

use app\base\rpc\Controller;
use twinkle\client\Client;
use Yii;

class DemoController extends Controller
{

    const DOMAIN_URL = 'http://www.uc.com';

    public function actionIndex()
    {
        return $this->renderPartial('sign');
    }

    public function actionSignIn()
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

    public function actionEdit()
    {

        $client = new Client('Yar', 'http://www.twinklephp.com/rpc/user');

        if (Yii::$app->request->isPost) {
            $editInfo = Yii::$app->request->post();
            $result = $client->editUser($editInfo['user_id'], $editInfo);
            return $this->renderPartial('edit', [
                'msg' => $result['status'] ? '保存失败' : '修改成功',
                'step' => 'save'
            ]);
        }

        $id = Yii::$app->request->get('user_id');
        $userInfo = $client->getUserById($id);
        return $this->renderPartial('edit', [
            'user_info' => $userInfo['user_info'],
            'step' => 'read'
        ]);
    }

    public function actionRegister()
    {
        return $this->renderPartial('register');
    }

    public function actionSignUp()
    {
        $username = Yii::$app->request->post('username', '');
        $password = Yii::$app->request->post('password', '');
        $repassword = Yii::$app->request->post('repassword', '');

        $client = new \Yar_Client(self::DOMAIN_URL . '/rpc/user');
        $data = array(
            'username' => $username,
            'password' => $password,
            'repassword' => $repassword,
        );
        $result = $client->register($data);
        if (isset($result['status']) && $result['status'] === 0) {
            $info = empty($result['user_info']) ? [] : $result['user_info'];
            return $this->renderPartial('register', [
                'info' => $info,
                'step' => 'login_success',
            ]);
        }

        return $this->renderPartial('register', ['step' => 'login_fail']);
    }

}
