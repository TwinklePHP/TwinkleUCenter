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
use yii\helpers\Markdown;

class SiteController extends Controller
{
    public function actionIndex()
    {

        $file = __DIR__.'/../../document/markdown/1.install.md';
        echo Markdown::process(file_get_contents($file),'gfm-comment');
    }

    public function actionTest()
    {
        print_r( Yii::t('app', 'email_can_not_empty'));
    }

    public function actionFlushSchema()
    {
        Yii::$app->db->schema->refresh();
        return 'schema清除成功';
    }

}