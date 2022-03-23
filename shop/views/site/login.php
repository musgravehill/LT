<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\FormLogin */

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
//
use yii\bootstrap\ActiveForm;
use \app\models\User;

$this->title = 'Вход';
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
        <?=
        $form->field($model, 'phone', [
            'template' => '
                        <div class="form-row m-0">
                            <div class="col-sm-12 m-0">
                                <b>{label}</b>
                            </div>
                            <div class="col-sm-12 m-0">
                                <div class="input-group">
                                     
                                    {input}
                                    {hint}
                                </div>
                                <div class="text-danger">
                                        {error}
                                </div>
                            </div>
                        </div>',
        ])->textInput([
            'placeholder' => 'Телефон',
            'type' => 'text',
            'imask' => 'phone',
            'autocomplete' => 'off',
        ]);
        ?>

        <?= $form->field($model, 'pass')->passwordInput(['placeholder' => 'Пароль', 'type' => 'password','autocomplete' => 'off',]) ?>
        <div class="form-row">            
            <div class="col-6 m-0">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-danger', 'name' => 'login-button']) ?>
            </div>
            
            <div class="col-6 m-0">
                <a href="<?= Url::to(['/site/initsetpass']); ?>"  class="btn btn-outline-secondary float-right">забыли пароль?</a>
            </div>             
        </div>

        <!--div class="form-row">
            <div class="col-12">
        <?= $form->errorSummary($model); ?>
            </div>
        </div-->
        <?php ActiveForm::end(); ?>
    </div>  
</div>


