<?php
/**
 * @var $this \yii\web\View
 * @var $cart_data \app\core\cart\ProductCount
 */
?>
<p>Заказано изделий: <?= $cart_data->all_num ?></p>
<p>На сумму: <?= yii::$app->formatter->asInteger($cart_data->sum) ?> руб.</p>
<p>Скидка <?= $cart_data->percent ?>%  (<?= yii::$app->formatter->asInteger($cart_data->discount) ?> руб.)</p>
<p>Итого: <?= yii::$app->formatter->asInteger($cart_data->total) ?> руб.</p>
