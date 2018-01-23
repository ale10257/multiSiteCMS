<?php

use app\assets\FancyBoxAsset;
use app\components\helpers\RemoveImgAdminHelper;
use app\modules\admin\assets\AdminSortableAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

FancyBoxAsset::register($this);
AdminSortableAsset::register($this);

/* @var $this yii\web\View */
/* @var $formModel \app\core\galleries\forms\GalleryForm */

$this->title = 'Галерея ' . $formModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Список галерей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-update">


    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header with-border">
                    <h2 class="box-title"><?= $this->title ?></h2>
                </div>

                <div class="box-body">

                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                    <?= $form->field($formModel, 'any_images[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label('Загрузка картинок для галереи') ?>

                    <div class="form-group">
                        <p><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></p>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header with-border">
                    <h2 class="box-title">Загруженные картинки для галереи</h2>
                </div>

                <div class="box-body">
                    <?php if($formModel->uploaded_images) : ?>
                        <div class="gallery-admin" data-sort=<?= \yii\helpers\Url::to(['sort-image']) ?>>
                            <?php foreach ($formModel->uploaded_images as $image) : ?>
                                <?php
                                /** @var  $image \app\core\galleries\forms\GalleryForm */
                                $img = RemoveImgAdminHelper::addElementRemove(
                                    $image->id, $formModel->webDir . $image->name,
                                    $this->context->id, 150, null, false
                                );
                                ?>
                                <div data-id="<?= $image->id ?>" class="item-gallery-admin-wrap">
                                    <div class="item-gallery-admin">
                                        <?= $img ?>
                                        <?= $this->render('_form_image', ['image' => $image]) ?>
                                    </div>
                                </div>
                            <? endforeach ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>


</div>