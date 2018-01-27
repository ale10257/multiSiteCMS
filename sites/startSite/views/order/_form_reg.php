<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17.11.17
 * Time: 19:46
 */

/**
 * @var $this \yii\web\View
 * @var  $formModel \app\core\cart\forms\OrderForm
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div class="row" style="margin-top: 10px;">
    <div class="col-xs-12 bg-white">
        <h2>Оформить заказ</h2>
    </div>
</div>

<div class="row data-cart content" style="padding-left: 0;">

    <?php $form = ActiveForm::begin(
        ['action' => ['/order/send-order']]
    ) ?>
    <div class="col-xs-12 col-md-6">
        <?= $form->field($formModel, 'first_name')->textInput() ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?= $form->field($formModel, 'last_name')->textInput() ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?= $form->field($formModel, 'email')->textInput() ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?= $form->field($formModel, 'phone')->textInput() ?>
    </div>

    <div class="col-xs-12 col-md-6">
        <?= $form->field($formModel, 'address')->textarea() ?>
    </div>

    <div class="col-xs-12 col-md-6">
        <?= $form->field($formModel, 'comment')->textarea() ?>
    </div>

    <div class="cxol-xs-12 col-md-6 form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php $form::end() ?>
</div>


