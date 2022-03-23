<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?> 

<?php
$form = ActiveForm::begin([
            //'layout' => 'horizontal',
            'options' => [
                'class' => '',
            ],
            'fieldConfig' => [
                'template' => '
                        <div class="mb-3">                             
                                <b>{label}</b>                               
                                {input}
                                {hint}
                                <div class="text-danger">
                                    {error}
                                </div>                            
                        </div>', //{beginWrapper} {endWrapper}
            /* 'horizontalCssClasses' => [
              'label' => 'col-sm-4',
              'offset' => 'zcol-sm-offset-4',
              'wrapper' => 'col-sm-4',
              'error' => 'text-danger',
              'hint' => 'text-danger'
              ] */
        ]]);
?>
<?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username'), 'autocomplete' => 'off', 'type' => 'text']) ?>
<?= $form->field($model, 'phone')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('phone'), 'autocomplete' => 'off', 'type' => 'text']) ?>
<?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('email'), 'autocomplete' => 'off', 'type' => 'email']) ?>
<div class="mb-3">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
</div> 
<?php ActiveForm::end(); ?>