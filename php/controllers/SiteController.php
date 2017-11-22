<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/12
 * Time: 17:32
 */

namespace app\controllers;

use app\models\UserAddress;
use Yii;
use app\base\Controller;

class SiteController extends Controller
{

    public function actionTest(){
        $userAddressModel = new UserAddress();
        print_r($userAddressModel->getOne(['user_id'=>1,'select'=>'count(1) as count']));
    }

    public function actionFlushSchema()
    {
        Yii::$app->db->schema->refresh();
        return 'schema清除成功';
    }

}