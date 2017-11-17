<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/12
 * Time: 17:32
 */

namespace app\controllers;

use Yii;
use app\base\Controller;
use app\models\UserInfo;

class SiteController extends Controller
{

    public function actionTest(){
        print_r((new UserInfo())->getKey());
    }

    public function actionFlushSchema()
    {
        Yii::$app->db->schema->refresh();
        return 'schema清除成功';
    }

}