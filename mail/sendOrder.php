<?php
/**
 * @var \app\core\cart\forms\UserData $user_data
 * @var \app\core\cart\repositories\OrderProductRepository[] $order_products
 * @var int $order_id
 * @var \app\core\cart\ProductCount $cart_data
 */
?>

<h2>Здравствуйте, <?= $user_data->firstName . ' ' . $user_data->lastName ?>!</h2>

<p>Вы успешно разместили заказ на сайте <?= yii::$app->name ?></p>
<p>ID заказа: <?= $order_id ?></p>
<h3>Ваши данные</h3>
<p>Емайл: <?= $user_data->email ?></p>
<p>Телефон: <?= $user_data->phone ?></p>
<p>Адрес: <?= $user_data->address ?></p>

<p>Комментарий:</p>
<p><?= nl2br($user_data->comment) ?></p>

<h2>Заказанные позиции</h2>

<table style="border: 1px solid #cecece; border-collapse: collapse;">
    <tr>
        <th style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
            Арт.
        </th>
        <th style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
            Картинка
        </th>
        <th style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
            Название
        </th>
        <th style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
            Кол-во
        </th>
        <th style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
            Цена
        </th>
        <th style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
            Сумма
        </th>
    </tr>

    <? foreach ($order_products as $product) : ?>
        <tr>
            <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
                <?= $product->product->code ?>
            </td>
            <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
                <?php $url = Yii::$app->urlManager->createAbsoluteUrl($product->image) ?>
                <img src="<?= $url ?>" style="width: 150px; height: auto;">
            </td>
            <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
                <?= $product->product->name ?>
            </td>
            <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
                <br>Заказано: <?= $product->count ?>
            </td>
            <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
                <?= yii::$app->formatter->asInteger($product->product->price) ?>
            </td>
            <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
                <?= yii::$app->formatter->asInteger($product->product->price * $product->count) ?>
            </td>
        </tr>
    <? endforeach ?>
    <tr>
        <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle" colspan="5">

        </td>
        <td style="border: 1px solid #cecece; border-collapse: collapse; padding: 5px; vertical-align: middle">
            <p>Заказано изделий: <?= $cart_data->all_num ?></p>
            <p>На сумму: <?= yii::$app->formatter->asInteger($cart_data->sum) ?> руб.</p>
            <p>Скидка <?= $cart_data->percent ?>%  (<?= yii::$app->formatter->asInteger($cart_data->discount) ?> руб.)</p>
            <p>Итого: <?= yii::$app->formatter->asInteger($cart_data->total) ?> руб.</p>
        </td>
    </tr>
</table>
