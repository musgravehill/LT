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

$this->title = 'Регистрация';
?>

<div class="row">
    <div class="col-sm-12 col-md-8">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-sm-12 col-md-8">
        <?php
        $form = ActiveForm::begin([
                    //'layout' => 'horizontal',
                    'options' => [
                        'class' => '',
                    ],
                    'fieldConfig' => [
                        'template' => '
                        <div class="form-row m-0">
                            <div class="col-sm-12 m-0">
                                <b>{label}</b>
                            </div>
                            <div class="col-sm-12 m-0">
                                {input}
                                {hint}
                                <div class="text-danger">
                                    {error}
                                </div>
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
        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email', 'autocomplete' => 'off','type'=>'email']) ?>
        <?= $form->field($model, 'pass')->passwordInput(['placeholder' => 'Пароль', 'autocomplete' => 'off',]) ?>
        <div class="form-row">
            <div class="col-6 m-0">
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-danger', 'name' => 'login-button']) ?>
            </div>            
        </div> 
        <?php ActiveForm::end(); ?>
    </div>
</div>
