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



<section class="py-5 text-center container">
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Обратная связь</h1>
            <p class="lead text-muted">
                Ваше мнение важно для нас.
            </p>
            <p>
                <a href="<?= Url::to(['feedback/cr']); ?>" class="btn btn-primary my-2">
                    Написать нам
                </a>
            </p>
        </div>
    </div>
</section>




