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
            <th></th>
            <th>Создано</th>
            <th></th>
        </tr>
        <?php foreach ($items as $item): ?>  
            <tr>
                <td>
                    <?= Html::encode($item['id']) ?>
                </td>                 
                <td>
                    <input data-feedback-username data-id="<?= Html::encode($item['id']) ?>" type="text" value="<?= Html::encode($item['username']) ?>" class="form-control"> 
                </td>                
                <td> 
                    <input data-feedback-email data-id="<?= Html::encode($item['id']) ?>" type="text" value="<?= Html::encode($item['email']) ?>" class="form-control"> 
                </td>
                <td>
                    <input data-feedback-phone data-id="<?= Html::encode($item['id']) ?>" type="text" value="<?= Html::encode($item['phone']) ?>" class="form-control"> 
                </td>
                <td>
                    <span data-feedback-save data-id="<?= Html::encode($item['id']) ?>" class="btn btn-outline-success btn-sm">
                        <span class="bi-save"></span>                         
                    </span>
                </td>
                <td>
                    <span data-moment="DD-MM-YYYY HH:mm"><?= Html::encode($item['dt_create']) ?></span>                    
                </td>

                <td>
                    <a href="" class="text-secondary">
                        <span class="bi-x-circle"></span>
                    </a>
                </td>
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


<script>


    document.addEventListener('DOMContentLoaded', function () {
        feedback_actions();
    });

    function feedback_actions() {
        const feedback_u_url = '<?= Url::to(['feedback/u']); ?>';
        const feedback_d_url = '<?= Url::to(['feedback/d']); ?>';
        const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');

        const btnsSave = Array.from(document.querySelectorAll('span[data-feedback-save]'));
        for (btn of btnsSave) {
            btn.addEventListener('click', (e) => {
                const id = parseInt(e.currentTarget.dataset.id) || 0;
                console.log(id);
            });
        }

        async function postData(url = '', data = {}) {
            const response = await fetch(url, {
                method: 'POST', // *GET, POST, PUT, DELETE, etc.
                mode: 'no-cors', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: {
                    //'Content-Type': 'application/json'
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                redirect: 'follow', // manual, *follow, error
                referrerPolicy: 'no-referrer', // no-referrer, *client
                body: data = Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&')
            });
            return await response.json(); // parses JSON response into native JavaScript objects
        }
    }

    postData(feedback_u_url, {id: 3, _csrf: csrfToken})
            .then((data) => {
                console.log(data);
            });
</script>

