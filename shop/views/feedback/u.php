<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

$this->title = 'Обратная связь';
?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-6 p-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?=
        $this->context->renderPartial('/feedback/form_u', [
            'model' => $model,
        ]);
        ?>
    </div>    
</div>
