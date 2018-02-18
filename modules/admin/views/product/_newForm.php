<?php
/* @var $formModel \app\core\products\forms\ProductForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <h3>Родительская категория: <?= $formModel->category ?></h3>
                <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'categories_id')->hiddenInput()->label(false) ?>
                <?= $form->field($formModel, 'sort')->dropDownList($formModel->sortArray) ?>
                <div class="text-right" class="form-group">
                    <?= Html::a('Вернуться к продуктам',
                        ['index', 'category_id' => $formModel->categories_id],
                        ['class' => 'btn btn-info']) ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
