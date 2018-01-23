<?php
/**
 * @var $formModel \app\core\user\auth\ResetPasswordForm
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="login-box">
    <div class="login-logo">
        <h3 style="text-align: left;">Восстановление пароля</h3>
    </div>
    <p>Ввведите новый пароль</p>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <?= $form->field($formModel, 'password')->passwordInput(['autofocus' => true]) ?>
        <div class="row">
            <div class="col-xs-4">
                <?= Html::submitButton('Ок', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
