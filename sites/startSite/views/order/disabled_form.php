<?php
/**
 * @var $this \yii\web\View
 * @var $productCount int
 */

use yii\helpers\Html;
?>

<div class="form-group">
    <?= Html::input('text', null, null, [
        'disabled' => true,
        'class' => 'short'
    ]) ?>
    <span style="display: none;" class="new-count"><?= $productCount ?></span>
</div>

<div class="form-group">
    <?= Html::button('Заказано', [
        'class' => 'btn btn-danger',
        'disabled' => true,
    ]) ?>
</div>
