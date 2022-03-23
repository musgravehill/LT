<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
?>

<?php foreach ($items as $item): ?>     
    <div class="card mb-3">
        <div class="card-header">
            <?= Html::encode($item['username']) ?>
            <span class="text-secondary float-end">
                <?= Html::encode($item['dt_create']) ?>
            </span>
        </div>
        <div class="card-body p-1">            
            <p class="m-1 p-0"><?= Html::encode($item['email']) ?></p>
            <p class="m-1 p-0"><?= Html::encode($item['phone']) ?></p>
        </div>
    </div>
<?php endforeach; ?>
 
