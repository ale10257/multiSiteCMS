<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $formModel \app\core\userReg\UserRegForm */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="box">
    <div class="box-body">
        <div class="user-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $this->render('/user-admin/_innerForm', ['form' => $form, 'formModel' => $formModel->user]) ?>

            <?= $this->render('_inner_form', ['form' => $form, 'formModel' => $formModel]) ?>

            <?= $this->render('/user-admin/_role_field', ['form' => $form, 'formModel' => $formModel->user]) ?>

            <?= $this->render('/user-admin/_check_box', ['form' => $form, 'formModel' => $formModel->user]) ?>

            <div class="form-group">
                <?= Html::submitButton('Ok', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


