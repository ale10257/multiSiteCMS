<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.11.17
 * Time: 16:04
 */

use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $order_product \app\core\cart\repositories\OrderProductRepository[]
 * @var $formModel \app\core\cart\forms\OrderForm
 * @var $cart_data \app\core\cart\ProductCount
 */

$this->title = 'Корзина товаров';
?>

<h1>Корзина</h1>

<?php if ($order_product) : ?>

    <? foreach (array_chunk($order_product, 4) as $product) : ?>
        <div class="row">
        <?php /** @var \app\core\cart\repositories\OrderProductRepository[] $product */ ?>
        <? foreach ($product as $item) : ?>
            <div class="col-md-3 text-center">
                <?= $this->render('_item_cart', ['item' => $item]) ?>
                <p>
                    <?= Html::a('Перейти к разделу', '/category/' . $item->product->category->alias,
                        ['style' => 'text-decoration: underline;']) ?>
                </p>
                <?= Html::a('<i class="fa fa-times"></i>', ['/order/delete', 'id' => $item->id], [
                    'title' => 'Удалить',
                    'class' => 'delete-prod',
                    'data' => [
                        'method' => 'post'
                    ]
                ]) ?>
            </div>
        <? endforeach ?>
    <? endforeach ?>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <?= $this->render('cart_data', ['cart_data' => $cart_data]) ?>
        </div>
    </div>
    <hr>
    <?= $this->render('_form_reg', ['formModel' => $formModel,]) ?>
<?php else: ?>
    <div class="row cart-wrap">
        <div class="col-xs-12">
            <h3>Ничего не заказано</h3>
        </div>
    </div>
<?php endif ?>





