<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel \app\core\discounts\DiscountForm */

$this->title = 'Скидка от  ' . $formModel->start_sum;
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <?= $form->field($formModel, 'start_sum') ?>
                <?= $form->field($formModel, 'percent') ?>

                <div class="form-group">
                    <p><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></p>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
