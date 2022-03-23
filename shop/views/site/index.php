<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;

$this->title = 'LT';

\Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => Html::encode($this->title),
]);
\Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => Html::encode($this->title),
]);
?>

<div class="row mt-3">
    <div class="col-12">

        <h1>LT</h1>



    </div>
</div>
