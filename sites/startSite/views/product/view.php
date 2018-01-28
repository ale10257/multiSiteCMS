<?php
/**
 * @var \yii\web\View $this
 * @var \app\core\products\repositories\ProductRepository $product
 * @var \app\core\cart\forms\OrderProductForm $formProduct
 * @var bool|int $checkProduct
 */

use yii\helpers\Html;
use app\assets\FancyBoxAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

FancyBoxAsset::register($this);

$this->title = $product->metaTitle ?: $product->name;

if ($product->metaDescription) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $product->metaDescription
    ]);
}

$this->params['breadcrumbs'][] = [
    'label' => $product->category->name,
    'url' => Url::to(['category/' . $product->category->alias]),
];
$this->params['breadcrumbs'][] = $product->name;

?>

<h1><?= Html::encode($product->name) ?></h1>

<div class="row">
    <div class="col-md-9">
        <a href="<?= $product->imagesGallery[0]->webPath ?>" data-fancybox="gallery-product">
            <?php $imgMain = $product->imagesGallery[0]->webThumbPath ?>
            <img class="img-responsive" src="<?= $imgMain ?>">
        </a>
        <?php unset($product->imagesGallery[0]) ?>
        <?php if ($product->imagesGallery) : ?>
            <? foreach ($product->imagesGallery as $image) : ?>
                <a href="<?= $image->webPath ?>" data-fancybox="gallery-product"></a>
            <? endforeach ?>
        <?php endif ?>
    </div>
    <div class="col-md-3">
        <h3>Описание продукта</h3>
        <?= $product->description ?>
        <p>Цена: <?= $product->price ?>р.</p>
        <p>Артикул: <?= $product->code ?></p>
        <?php if (!$checkProduct) : ?>
            <?php
            Pjax::begin([
                'enablePushState' => false
            ]);
            echo '<p>В наличии: ' . $product->count . 'шт.</p>';
            $form = ActiveForm::begin([
                'action' => Url::to(['product/add']),
                'options' => [
                    'data' => ['pjax' => '']
                ]
            ]) ?>
            <?= $form->field($formProduct, 'count') ?>
            <?= $form->field($formProduct, 'product_id')->hiddenInput(['value' => $product->id])->label(false) ?>
            <?= $form->field($formProduct, 'image')->hiddenInput(['value' => $imgMain])->label(false) ?>
            <div class="form-group">
                <p><?= Html::submitButton('Заказать', ['class' => 'btn btn-success']) ?></p>
            </div>
            <?php
            ActiveForm::end();
            Pjax::end() ?>
        <?php else : ?>
            <?= $this->render('_ordered_form', ['count' => $product->count]) ?>
        <?php endif ?>
    </div>
</div>

