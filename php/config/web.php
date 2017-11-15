<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/12
 * Time: 16:05
 */

return [
    'id' => 'twinkledeals',
    'basePath' => dirname(__DIR__),
    'language' => 'en', //默认语言
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => '9nfRpkQ9RZYk8TzAVMsVeThqLePM9HdR',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '<controller:rpc>/<name:[\w-]+><nouse:(.*)>' => '<controller>/index',
                '<controller:[\w-]+>/<action:[\w-]+><nouse:(.*)>' => '<controller>/<action>',
                '<controller:[\w-]+><nouse:(.*)>' => '<controller>/index',
            ],
        ],
    ]
];