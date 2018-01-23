<?php

use app\components\widgets\redactor2\Redactor;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel \app\core\chunks\ChunkForm */

$this->title = 'Чанк  ' . $formModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Чанки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <?= $form->field($formModel, 'name') ?>
                <?= $form->field($formModel, 'alias') ?>
                <?= $form->field($formModel, 'description')->textarea() ?>
                <?= $form->field($formModel, 'text')->widget(Redactor::className(), [
                    'ctrl_save' => true,
                ]) ?>
                <div class="form-group">
                    <p><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></p>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
