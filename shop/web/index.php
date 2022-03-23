<?php

error_reporting(0);

if ($_SERVER['SERVER_NAME'] == 'lt.ru') {
    defined('YII_ENV') or define('YII_ENV', 'dev');
    error_reporting(E_ALL);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
} else {
    defined('YII_ENV') or define('YII_ENV', 'prod');
}

if (isset($_GET['dbg']) || isset($_POST['dbg'])) {
    error_reporting(E_ALL);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();

 







