<?php

$db = require(__DIR__ . '/_db.php');
$lifetimeLogin = 60; //60s
$enablePrettyUrl = true;

$config = [
    'id' => 'bn2console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\commands',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@webroot' => dirname(dirname(__FILE__)) . '/web',
    ],
    'components' => [
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\YiiAsset' => [
                    'js' => [
                        'yii.js'
                    ]
                ],
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'jquery.min.js',
                    ]
                ],
            ],
            'appendTimestamp' => true,
        ],
        'cache' => new yii\caching\DummyCache,
        'log' => [
            'traceLevel' => 0,
        ],
        'db' => $db,
        'urlManager' => [
            'hostInfo' => 'https://lt.com', /////console
            'baseUrl' => 'https://lt.com/', /////console
            'enablePrettyUrl' => $enablePrettyUrl,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                'logininit' => 'site/logininit',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'zakupki' => 'adm/purchase',
                'p/<id:\d+>.html' => 'product/view', //-<ufu:[0-9a-zA-Z\-]+>
                'sp/<id:\d+>.html' => 'staticpage/view',
                'sn/<id:\d+>.html' => 'staticnews/view',
                'chat/<item_type_id:\d+>/<item_id:\d+>' => 'chat/view',
                'u/<id:\d+>/<checksum:[\d\w]{16,16}>' => 'user/view',
                'c/<id:\d+>-<ufu:[0-9a-zA-Z\-]+>.html' => 'company/view',
                'c/<id:\d+>' => 'company/view',
                '<controller:[\w\-_]+>/<action:[\w\-_]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w\-_]+>/<action:[\w\-_]+>' => '<controller>/<action>',
            ],
        ],
        'i18n' => [
            'translations' => [
                'vendor*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/translates',
                ],
            ],
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'name' => 'bzn',
            'timeout' => $lifetimeLogin,
            'cookieParams' => [
                'httponly' => true,
                'lifetime' => $lifetimeLogin,
            ],
            'useCookies' => true,
        ],
    ],
    'params' => [//HelperY::params('lifetimeLogin')        
        'lifetimeLogin' => $lifetimeLogin,
        'domain' => 'beznalom.com',
    ],
];

return $config;
