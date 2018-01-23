<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $formModel \app\core\user\auth\LoginEmailForm */

$this->title = 'Sign In';

$this->title = 'Вход на сайт';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="row ml-15">
    <div class="col-md-6 content person-data-15">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="login-box-body">
                <h3 class="login-box-msg"><?= $this->title ?></h3>

                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

                <?= $form->field($formModel, 'email', $fieldOptions1) ?>

                <?= $form->field($formModel, 'password', $fieldOptions2) ?>

                <?= $form->field($formModel, 'rememberMe')->checkbox(['checked' => 'checked']) ?>

                <div class="form-group">
                    <?= Html::submitButton('Войти',
                        ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <!-- /.social-auth-links -->
            <?= Html::a('Забыли пароль?', ['/login/request-password-reset'], ['class' => 'request-pswd']) ?>

        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
</div>
