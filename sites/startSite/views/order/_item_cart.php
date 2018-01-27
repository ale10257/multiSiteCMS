<?php
/**
 * @var $this \yii\web\View
 * @var $item \app\core\cart\repositories\OrderProductRepository
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
    <?= Html::img($item->image, ['class' => 'img-responsive']) ?>
    <p>арт. <?= $item->product->code ?></p>
    <p class="strong"><?= $item->product->name ?></p>
    <p>Цена: <?= $item->product->price ?>р.</p>
    <p>Заказано: <?= $item->count ?></p>
    <?php $sum = ($item->count) * $item->product->price ?>
    <p class="strong">На сумму: <?= yii::$app->formatter->asInteger($sum) ?> руб.</p>
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['/order/change-order', 'id' => $item->id]),
    ]) ?>
    <?= $form->field($item->form, 'count')->textInput([
        'placeholder' => 'кол-во',
        'value' => '',
        'autocomplete' => 'off'
    ])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('Изменить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>


