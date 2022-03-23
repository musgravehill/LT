<?php

namespace app\commands;

use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;
//use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
//
use app\components\HelperY;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\console\Controller;

class CronController extends Controller {

    public function actionEveryminute() {
        
    }

}

/*
 crontab -e    
 * * * * * /var/www/lt/shop/yii cron/everyminute
 

cd /var/www/lt && git stash && git pull && chown -R www-data:www-data /var/www/lt
  && chmod 774 /var/www/lt 
 */
