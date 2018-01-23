<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var $model \app\core\user\auth\PasswordResetRequestForm
 */

$this->title = 'Запрос для восстановления пароля';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="login-box">
    <div class="login-logo">
        <h3 style="text-align: left;"><?= Html::encode($this->title) ?></h3>
    </div>
    <small>Письмо для восстановления пароля будет отправлено на емайл, указанный при
        регистрации.</small>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
        <div class="row">
            <div class="col-xs-4">
                <?= Html::submitButton('Ок', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
<!-- /.login-box-body -->

