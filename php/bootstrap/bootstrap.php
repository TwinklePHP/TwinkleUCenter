<?php
/**
 * 引导文件
 */
defined('YII_ENV') || define('YII_ENV', empty($_SERVER['ENV']) ? 'prod' : $_SERVER['ENV']);
defined('YII_DEBUG') || define('YII_DEBUG', ('dev' == YII_ENV || (isset($_GET['debug']) && $_GET['debug'] == 1)));

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
Yii::$classMap['app\base\Application'] = __DIR__ . '/../base/Application.php';

$config = require(__DIR__ . '/../config/web.php');

$app = new \app\base\Application($config);