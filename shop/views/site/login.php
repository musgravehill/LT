<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\FormRegister */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
//
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Вход';
?>

<div class="row">
    <div class="col-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-12">
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
        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('email'), 'autocomplete' => 'off', 'type' => 'email']) ?>
        <?= $form->field($model, 'pass')->passwordInput(['placeholder' => $model->getAttributeLabel('pass'), 'autocomplete' => 'off',]) ?>
        <div class="mb-3">   
            <?= Html::submitButton('Войти', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
        </div> 
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-12">
        <p class="text-secondary">
            admin@lt.ru 
            12345678
        </p>
    </div>
</div>


