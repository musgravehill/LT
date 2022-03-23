<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

$this->title = 'Обратная связь';
?>

<div class="row">
    <h1>Обратная связь</h1>

    <table class="table table-sm">
        <tr>
            <th>id</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Создано</th>
            <th></th>
        </tr>
        <?php foreach ($items as $item): ?>  
            <tr>
                <td><?= Html::encode($item['id']) ?></td>
                <td><?= Html::encode($item['username']) ?></td>                
                <td> <?= Html::encode($item['email']) ?></td>
                <td><?= Html::encode($item['phone']) ?></td>
                <td>
                    <span data-moment="DD-MM-YYYY HH:mm"><?= Html::encode($item['dt_create']) ?></span>                    
                </td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="row">
    <div class="col-12 mb-5 mt-1">
        <?php
        $urlParamsTmp = $urlParams;
        $urlParamsTmp['page'] = $urlParamsTmp['page'] - 1;
        ?>
        <?php if ($urlParamsTmp['page'] > 0): ?>
            <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="helper-font-30">
                <span class="btn btn-outline-dark btn-sm">назад</span>
            </a>
        <?php endif; ?>
        <?php
        $urlParamsTmp = $urlParams;
        $urlParamsTmp['page'] = $urlParamsTmp['page'] + 1;
        ?>
        <a href="<?= Url::toRoute($urlParamsTmp) ?>" class="float-end helper-font-30">
            <span class="btn btn-outline-dark btn-sm">дальше</span>
        </a>
    </div>
</div>

