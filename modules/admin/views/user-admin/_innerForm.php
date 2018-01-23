<?php
/* @var $formModel \app\core\user\forms\UserAdminEditForm */
/* @var $this yii\web\View */
?>
<?= $form->field($formModel, 'first_name') ?>
<?= $form->field($formModel, 'last_name') ?>
<?= $form->field($formModel, 'email')->textInput(['maxlength' => true]) ?>
<?= $form->field($formModel, 'passwd') ?>


