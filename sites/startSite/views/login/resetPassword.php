<?php

/**
 * @var $model \app\core\user\auth\ResetPasswordForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Восстановление пароля';
?>

<div class="row ml-15">
    <div class="col-md-6 content person-data-15">
        <h3 style="text-align: left;"><?= Html::encode($this->title) ?></h3>
        <p>Ввведите новый пароль</p>
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton('Ок', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
