<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var \app\core\user\auth\ResetPasswordForm $model
 */

$this->title = 'Запрос для восстановления пароля';
?>
<div class="row ml-15">
    <div class="col-md-6 content person-data-15">
        <h3><?= Html::encode($this->title) ?></h3>
        <p>
            Письмо для восстановления пароля будет отправлено на емайл, указанный при регистрации.
        </p>
        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton('Ок', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- /.login-box-body -->

