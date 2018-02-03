<?php
/* @var $this yii\web\View */
/* @var $formModel \app\core\articles\forms\ArticleForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories_id array|object */
/* @var $new boolean */

use app\modules\admin\assets\AdminSortableAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\components\helpers\RemoveImgAdminHelper;

AdminSortableAsset::register($this);

?>
<div class="row">
    <?php if (!$new) : ?>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <p class="text-right"><a class="show-on-site" target="_blank" href="<?= $formModel->link ?>">Посмотреть
                            статью на сайте</a></p>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
<div class="row">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'form-ctrl-save'
        ]
    ]); ?>
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($formModel, 'categories_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($formModel->categories, 'id', 'name'),
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Родительская категория'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
                <?php if (!$new) : ?>
                    <?= $form->field($formModel, 'short_text')->widget(Widget::className(), [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 150,
                        ],
                    ]) ?>
                    <?= $form->field($formModel, 'text')->widget(Widget::className(), [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 350,
                            'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => $formModel->webDir]),
                            'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => $formModel->webDir]),
                            'plugins' => [
                                'table',
                            ],
                        ],
                    ]) ?>
                <?php endif ?>
                <div class="form-group text-right">
                    <p><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></p>
                </div>
                <?php if (!$new) : ?>
                    <?= $form->field($formModel, 'any_images[]')->fileInput([
                        'multiple' => true,
                        'accept' => 'image/*'
                    ])->label('Загрузка картинок для галереи в конце статьи') ?>
                <?php endif ?>


            </div>
        </div>
    </div>

    <?php if (!$new) : ?>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($formModel, 'alias')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($formModel, 'metaTitle')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($formModel, 'metaDescription')->textarea(['maxlength' => true, 'rows' => 2]) ?>
                    <?= $form->field($formModel, 'active')->checkbox() ?>
                    <?= $form->field($formModel, 'one_image')->fileInput() ?>
                    <?php if ($formModel->image) : ?>
                        <h4>Выбранное изображение для статьи</h4>
                        <?= RemoveImgAdminHelper::addElementRemove(
                            $formModel->id,
                            $formModel->webDir . $formModel->image,
                            $this->context->id,
                            200,
                            'delete-main-image'
                        );
                        ?>
                    <?php endif ?>
                </div>
            </div>

        </div>
    <?php endif ?>
    <?php ActiveForm::end(); ?>
</div>

<?php if (!$new) : ?>
    <div class="box">
        <div class="box-body">
            <div class="box-header with-border">
                <h2 class="box-title">Загруженные картинки для галереи в конце статьи</h2>
            </div>
            <?php if ($formModel->uploaded_images) : ?>
                <div class="gallery-admin" data-sort=<?= Url::to(['sort-image']) ?>>
                    <?php foreach ($formModel->uploaded_images as $image) : ?>
                        <?php
                        /** @var  $image \app\core\articles\forms\ArticleImageForm */
                        $img = RemoveImgAdminHelper::addElementRemove(
                            $image->id, $formModel->webDir . $image->name,
                            $this->context->id, 200, null, false
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
<?php endif ?>
