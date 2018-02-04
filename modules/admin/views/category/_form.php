<?php

use app\components\helpers\RemoveImgAdminHelper;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\core\categories\CategoryRepository as Category;

/* @var $this yii\web\View */
/* @var $formModel  app\core\categories\CategoryForm */
/* @var $title string */

$options = [];

if ($formModel->alias == Category::RESERVED_ALIAS_ARTICLE || $formModel->alias == Category::RESERVED_ALIAS_PRODUCT) {
    $options = ['disabled' => true];
}
?>

<div class="col-md-6">
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title"><?= $title ?></h2>
        </div>
        <div class="box-body  target-block">

            <div class="menu-admin-form">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-ctrl-save']]); ?>
                <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'alias')->textInput($options) ?>
                <?= $form->field($formModel, 'parent')->textInput(['disabled' => true]) ?>
                <?php if(!$formModel->type_category_array) : ?>
                    <?= $form->field($formModel, 'name_type_category')->textInput(['disabled' => true])  ?>
                    <?= $form->field($formModel, 'type_category')->hiddenInput()->label(false)  ?>
                <?php else : ?>
                    <?= $form->field($formModel, 'type_category')->widget(Select2::classname(), [
                        'data' => $formModel->type_category_array,
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Тип категории'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                <?php endif ?>
                <?= $form->field($formModel, 'icon')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'metaDescription')->textarea() ?>
                <?= $form->field($formModel, 'metaTitle')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'active')->checkbox() ?>

                <?php if($formModel->type_category == 'article') : ?>
                    <?= $form->field($formModel, 'multiple')->checkbox() ?>
                <?php endif ?>
                <?= $form->field($formModel, 'one_image')->widget(FileInput::class, [
                    'options' => [
                        'accept' => 'image/*',
                        'multiple' => false
                    ],
                    'pluginOptions' => [
                        'showRemove' => true,
                        'showUpload' => false,
                    ]
                ]) ?>
                <div class="form-group">
                    <div>
                        <?= Html::submitButton('Ok', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('Скрыть форму', ['index'], ['class' => 'btn btn-info']) ?>
                    </div>
                    <?php if ($formModel->image) : ?>
                        <h4>Картинка категории</h4>
                        <?php
                        $image = RemoveImgAdminHelper::addElementRemove($formModel->id, $formModel->web_img,
                            $this->context->id, 200);
                        echo Html::tag('div', $image, ['class' => 'view-img-category-admin']);
                        ?>
                    <?php endif ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

