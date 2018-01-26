<?php
use yii\helpers\Html;
?>
<div class="form-group">
    <?= Html::input('text', null, null, ['disabled' => true]); ?>
</div>
<div class="form-group">
    <p><?= Html::button('Заказано', ['class' => 'btn btn-danger']) ?></p>
</div>