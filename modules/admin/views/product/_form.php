<?php
/* @var $this yii\web\View */
/* @var $formModel \app\core\products\forms\ProductForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $new boolean */

use app\components\helpers\RemoveImgAdminHelper;
use kartik\select2\Select2;
use vova07\imperavi\Widget;
use app\modules\admin\assets\AdminSortableAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use app\assets\FancyBoxAsset;

AdminSortableAsset::register($this);
FancyBoxAsset::register($this);

$link = Html::a('Посмотреть продукт на сайте', ['/product/view/', 'id_alias' => $formModel->alias],
    ['target' => '_blank', 'class' => 'show-on-site']);
?>

<?php if (!$new) : ?>
    <div class="row">
        <div class="col-md-7">
            <div class="box">
                <div class="box-body">
                    <p class="text-right"><?= $link ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="row">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-ctrl-save']]); ?>
    <div class="col-md-7">
        <div class="box">
            <div class="box-body">
                <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'categories_id')->widget(Select2::classname(), [
                    'data' => $formModel->categories,
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Родительская категория'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
                <?php if (!$new) : ?>
                    <?= $form->field($formModel, 'alias')->textInput(['maxlength' => true]) ?>
                    <div id="redactor-box">
                        <?php try {
                            echo Tabs::widget([
                                'items' => [
                                    [
                                        'label' => 'Общее описание',
                                        'content' => $form
                                            ->field($formModel, 'description')
                                            ->textarea()
                                            ->label(false)
                                            ->widget(Widget::className(), [
                                                'settings' => [
                                                    'lang' => 'ru',
                                                    'minHeight' => 350,
                                                    'plugins' => [
                                                        'table',
                                                    ],
                                                ],
                                            ]),
                                        'active' => true
                                    ],
                                ]
                            ]);
                        } catch (Exception $e) {
                        }
                        ?>
                    </div>
                    <?= $form->field($formModel, 'sort')->hiddenInput()->label(false) ?>
                    <?= $form->field($formModel, 'any_images[]')->fileInput([
                        'multiple' => true,
                        'accept' => 'image/*'
                    ]) ?>
                <?php endif ?>
                <div class="text-right" class="form-group">
                    <?= Html::a('Вернуться к продуктам',
                        ['index', 'category_id' => $formModel->categories_id],
                        ['class' => 'btn btn-info']) ?>
                    <?php if (!$new) : ?>
                        <?= Html::a('Добавить еще', ['create', 'category_id' => $formModel->categories_id],
                            ['class' => 'btn btn-primary']) ?>
                    <?php endif ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>

            </div>
        </div>
    </div>
    <?php if (!$new) : ?>
        <div class="col-md-5">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($formModel, 'metaTitle')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($formModel, 'metaDescription')->textarea() ?>
                    <? //= $form->field($formModel, 'showOnMainPage')->checkbox() ?>
                    <?= $form->field($formModel, 'active')->checkbox() ?>
                    <?= $form->field($formModel, 'new_prod')->checkbox() ?>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($formModel, 'price')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($formModel, 'old_price') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($formModel, 'code')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($formModel, 'count')->textInput() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
<?php ActiveForm::end(); ?>

<?php if (is_array($formModel->uploaded_images) && $formModel->uploaded_images) : ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Картинки продукта</h2>
            <div class="box">
                <div class="box-body">
                    <div class="gallery-admin" data-sort=<?= \yii\helpers\Url::to('sort-image') ?>>
                        <?php foreach ($formModel->uploaded_images as $image) : ?>
                            <?php
                            /** @var  $image \app\core\products\forms\ProductImageForm */
                            $img = RemoveImgAdminHelper::addElementRemove(
                                $image->id,
                                $formModel->webDir . $image->name,
                                $this->context->id,
                                250,
                                null, false
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
                </div>
            </div>

        </div>
    </div>
<?php endif ?>




