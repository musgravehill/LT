<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
?>



<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="">LT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">                
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['feedback/cr']); ?>">Feedback</a>
                </li>                 
            </ul>            
            <div class="navbar-nav">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <div class="nav-item text-nowrap">
                        <a class="nav-link px-3" href="<?= Url::to(['site/profile']); ?>">
                            <?= Html::encode(Yii::$app->user->identity->userEntity->email) ?>
                        </a>                    
                    </div>
                    <div class="nav-item text-nowrap">
                        <a class="nav-link px-3" href="<?= Url::to(['site/logout']); ?>">Logout</a>                    
                    </div>
                <?php else: ?>
                    <div class="nav-item text-nowrap">
                        <a class="nav-link px-3" href="<?= Url::to(['site/register']); ?>">Register</a>                    
                    </div>
                    <div class="nav-item text-nowrap">
                        <a class="nav-link px-3" href="<?= Url::to(['site/login']); ?>">Login</a>                    
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>




