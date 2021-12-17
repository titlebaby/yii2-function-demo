<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //Yii2 配置接收 json参数
        'request' => [
            'enableCookieValidation' => true,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

    ],
];
