<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use app\assets\AppAsset;
//
use app\models\User;

//

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>

        <link rel="icon" href="/favicon-32x32.png" sizes="32x32" type="image/png">

        <meta name="theme-color" content="#7952b3">
        <title><?= Html::encode($this->title) ?></title>         
        
        <?php $this->head() ?>
    </head>

    <body>
        <?php $this->beginBody() ?>
        <?= $this->render('_top_nav') ?>        
        <main class="container">
            <div class="p-1">
                <div class="row">
                    <?php foreach (Yii::$app->session->getAllFlashes() as $key => $messages) : ?>
                        <?php foreach ($messages as $message) : ?>
                            <div class="col">
                                <div class="m-1 alert alert-<?= $key ?> alert-dismissible fade show" role="alert">                                    
                                    <?= $message ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>                    
                </div>
                <?= $content ?>
            </div>
        </main>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
