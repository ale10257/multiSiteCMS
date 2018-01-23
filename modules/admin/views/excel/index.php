<?php
/**
 * @var $this \yii\web\View
 * @var $formModel \app\core\excel\ExcelForm
 * @var $balanceForm \app\core\excel\ExcelBalanceForm
 * @var $categories array
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;

$this->params['breadcrumbs'][] = 'Excel';

?>

<div class="row">

    <div class="col-md-4">
        <div class="box excel">
            <div class="box-body">
                <h2>Excel Action</h2>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],]); ?>
                <?= $form->field($formModel, 'category')->widget(Select2::classname(), [
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите категорию'],
                    'data' => $formModel->category_array,
                ]); ?>
                <?= $form->field($formModel, 'action')->dropDownList($formModel->action, [
                        'prompt' => 'Выберите действие'
                ]); ?>
                <?= $form->field($formModel, 'file')->fileInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Ok', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="box excel">
            <div class="box-body">
                <h2>Загрузить остатки</h2>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],]); ?>
                <?= $form->field($balanceForm, 'file')->fileInput() ?>
                <?= $form->field($balanceForm, 'clearBalance')->checkbox() ?>
                <?= $form->field($balanceForm, 'action')->hiddenInput()->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton('Ok', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>





