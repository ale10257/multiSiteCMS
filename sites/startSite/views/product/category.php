<?php
/**
 * @var \yii\web\View $this
 * @var \app\core\products\getProducts\DataCategory $dataCategory
 * @var \app\core\products\repositories\ProductRepository[] $products
 */

use yii\helpers\Url;
use app\assets\FancyBoxAsset;
use yii\helpers\Html;

FancyBoxAsset::register($this);

$this->title = $dataCategory->metaTitle;
if ($dataCategory->metaDescription) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $dataCategory->metaDescription
    ]);
}

$this->params['breadcrumbs'][] = $dataCategory->title;

/** @var \app\core\products\repositories\ProductRepository[] $arrProducts */
$arrProducts = array_chunk($dataCategory->products, 3);
?>
<h1><?= Html::encode($dataCategory->title) ?></h1>
<? foreach ($arrProducts as $products) : ?>
    <div class="row">
        <? foreach ($products as $product) : ?>
            <div class="col-md-4">
                <div class="thumbnail">
                    <a href="<?= $product->imagesGallery->webPath ?>" data-fancybox="product<?= $product->id ?>">
                        <img src="<?= $product->imagesGallery->webThumbPath ?>">
                    </a>
                    <div class="caption">
                        <h3><?= $product->name ?></h3>
                        <?php if ($product->old_price) : ?>
                            <p class="old-price">Старая цена: <?= $product->old_price ?>р.</p>
                        <?php endif ?>
                        <p>Цена: <?= $product->price ?>р.</p>
                        <p>В наличии: <?= $product->count ?></p>
                        <p>
                            <a href="<?= Url::to(['/product/view', 'id_alias' => $product->id]) ?>"
                               class="btn btn-primary">Подробнее</a>
                        </p>
                    </div>
                    <?php if ($product->new_prod) : ?>
                        <p class="new-prod">Новинка</p>
                    <?php endif ?>
                </div>
            </div>
        <? endforeach ?>
    </div>
<? endforeach ?>
