<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'bootstrap/css/bootstrap.min.css',
        'css/site.css',
    ];
    public $js = [
        'bootstrap/js/bootstrap.bundle.min.js',
        'moment/moment.min.js',
        'helper.js',
    ];
    public $depends = [
        //'yii\web\JqueryAsset',
        'yii\web\YiiAsset', //add jQuery.2 and yii.js for   jQuery().yiiActiveForm
            //'yii\bootstrap\BootstrapAsset', //css
            //'yii\bootstrap\BootstrapPluginAsset', //js
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
        //'async' => 'async',
        'defer' => true,
    ];
    public $cssOptions = [
        'async' => 'async',
            //'position' => \yii\web\View::POS_END
    ];

}
