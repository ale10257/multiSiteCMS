<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $formModel \app\core\adminMenu\forms\MenuAdminForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $auth_item array */
/* @var $title string */

?>

<div class="col-md-6">
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title"><?= $title ?></h2>
        </div>
        <div class="box-body  target-block">
            <div class="menu-admin-form">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'description')->textarea() ?>
                <?= $form->field($formModel, 'title')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'parent')->textInput([
                    'disabled' => 'disabled'
                ]) ?>
                <?= $form->field($formModel, 'icon')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'show_in_sidebar')->checkbox() ?>

                <?php if($formModel->roles) : ?>
                <?= $form->field($formModel, 'selectedRoles')->checkboxList($formModel->roles)->label('Разрешения для ролей') ?>
                <?php endif ?>

                <div>
                    <?= Html::submitButton('Ok', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Скрыть форму', ['index'], ['class' => 'btn btn-info']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


