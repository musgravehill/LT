<?php

$db = require(__DIR__ . '/_db.php');

$lifetimeLogin = 3600 * 24 * 14; //14 days

$config = [
    'id' => 'lt',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log',],
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
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
        'request' => [
            'cookieValidationKey' => 'NGTcVa18-6E5SWAapL7oHtTF_HJUxYPj',
            'enableCsrfValidation' => true,
        ],
        'cache' => new yii\caching\DummyCache,
        'user' => [
            'identityClass' => 'app\components\UserIdentity',
            'enableAutoLogin' => true,
            'authTimeout' => $lifetimeLogin,
            'loginUrl' => ['site/login',],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 5 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                'login' => 'site/login',
                'register' => 'site/register',
                'logout' => 'site/logout',
                'p/<id:\d+>.html' => 'product/view', //-<ufu:[0-9a-zA-Z\-]+>
                'sp/<id:\d+>.html' => 'staticpage/view',
                'chat/<item_type_id:\d+>/<item_id:\d+>' => 'chat/view',
                'u/<id:\d+>/<checksum:[\d\w]{16,16}>' => 'user/view',
                'c/<id:\d+>-<ufu:[0-9a-zA-Z\-]+>.html' => 'company/view',
                'c/<id:\d+>' => 'company/view',
                'brand/<id:\d+>-<ufu:[0-9a-zA-Z\-]+>.html' => 'brand/view',
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
        //'savePath' => __DIR__ . '/../tmp',
        ],
    ],
    'params' => [//HelperY::params('domain')       
        'lifetimeLogin' => $lifetimeLogin,
    ],
];

if (YII_ENV_DEV) {

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
