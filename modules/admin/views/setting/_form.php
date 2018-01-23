<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $formModel \app\core\settings\form\SettingChildForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $title string */
?>

<div class="col-md-6">
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title"><?= $title ?></h2>
        </div>
        <div class="box-body">
            <div class="settings-form">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>

                <?php $options = array_key_exists($formModel->alias, $formModel->reserved) ? ['disabled' => true] : [] ?>

                <?= $form->field($formModel, 'alias')->textInput($options               ) ?>


                <?= $form->field($formModel, 'icon')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'value')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'active')->checkbox() ?>
                <div class="form-group">
                    <?= Html::submitButton('Ok', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Скрыть форму', ['index'], ['class' => 'btn btn-info']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
