<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $cart_data \app\core\cart\ProductCount */
/* @var $model \app\core\cart\repositories\OrderRepository */
/* @var $formModel \app\core\cart\forms\OrderProductForm */

$this->title = 'Заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h2><?= $this->title ?></h2>
                    <?php if ($data = json_decode($model->data)) : ?>
                        <?php
                        $f = !(empty($data->first_name)) ? $data->first_name : $data->firstName;
                        $l = !(empty($data->last_name)) ? $data->last_name : $data->lastName;
                        ?>
                        <h3>Пользователь <?= $f . ' ' . $l ?></h3>
                    <?php else : ?>
                        <h3>Пользователь No name</h3>
                    <?php endif ?>

                </div>
                <div class="box-body">
                    <table>
                        <tr>
                            <th>
                                Арт.
                            </th>
                            <th>
                                Картинка
                            </th>
                            <th>
                                Название
                            </th>
                            <th>
                                Кол-во
                            </th>
                            <th>
                                Цена
                            </th>
                            <th>
                                Сумма
                            </th>
                            <th></th>
                            <th style="width: 100px;"></th>
                        </tr>

                        <? foreach ($model->orderProducts as $orderProduct) : ?>
                            <tr>
                                <td>
                                    <?= $orderProduct->product->code ?>
                                </td>
                                <td>
                                    <?php $url = Yii::$app->urlManager->createAbsoluteUrl($orderProduct->image) ?>
                                    <img src="<?= $url ?>" style="width: 150px; height: auto;">
                                </td>
                                <td>
                                    <?= $orderProduct->product->name ?>
                                </td>
                                <td>
                                    Со склада: <?= $orderProduct->from_stocke ?>
                                    <br>На заказ: <?= $orderProduct->to_order ?>
                                    <?php $all = $orderProduct->from_stocke + $orderProduct->to_order ?>
                                    <br>Всего: <?= $all ?>
                                </td>
                                <td>
                                    <?= yii::$app->formatter->asInteger($orderProduct->product->price) ?>
                                </td>
                                <td>
                                    <?= yii::$app->formatter->asInteger($orderProduct->product->price * $all) ?>
                                </td>
                                <td>
                                    <?php
                                    if ($model->status != $model::STATUS_ORDER_CLOSED) {
                                        echo Html::a('<span class="glyphicon glyphicon-trash"',
                                            ['delete-item', 'id' => $orderProduct->id],
                                            [
                                                'aria-label' => 'Удалить',
                                                'title' => 'Удалить',
                                                'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                                'data-method' => 'post',
                                            ]
                                        );
                                    } else {
                                        echo Html::tag('p', 'Отправлено');
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php if ($model->status != $model::STATUS_ORDER_CLOSED) : ?>
                                        <?php
                                        $form = ActiveForm::begin([
                                            'action' => Url::to(['change-num', 'id' => $orderProduct->id])
                                        ]);
                                        ?>
                                        <?= $form->field($orderProduct->form,
                                            'num')->textInput(['placeholder' => 'кол-во'])->label(false) ?>
                                        <?= $form->field($orderProduct->form,
                                            'product_id')->hiddenInput()->label(false) ?>
                                        <div class="form-group">
                                            <?= Html::submitButton('Изменить', ['class' => 'btn btn-success']) ?>
                                        </div>

                                        <?php ActiveForm::end() ?>
                                    <?php endif ?>
                                </td>

                            </tr>
                        <? endforeach ?>
                        <tr>
                            <td colspan="7">
                                <div class="pull-right">
                                    <p>Заказано изделий: <?= $cart_data->all_num ?></p>
                                    <p>На сумму: <?= yii::$app->formatter->asInteger($cart_data->sum) ?> руб.</p>
                                    <p>Скидка <?= $cart_data->percent ?>%
                                        (<?= yii::$app->formatter->asInteger($cart_data->discount) ?> руб.)</p>
                                    <p>Итого: <?= yii::$app->formatter->asInteger($cart_data->total) ?> руб.</p>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </table>

                    <?php if ($model->status != $model::STATUS_ORDER_CLOSED) : ?>
                        <div class="row">
                            <div class="col-md-2">

                                <h4>Добавить продукт в заказ по артикулу</h4>

                                <?php $form = ActiveForm::begin([
                                    'action' => Url::to(['add-product'])
                                ]);
                                ?>

                                <?= $form->field($formModel, 'code') ?>
                                <?= $form->field($formModel, 'num') ?>
                                <?= $form->field($formModel,
                                    'order_id')->hiddenInput(['value' => $model->id])->label(false) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                                </div>

                                <?php ActiveForm::end() ?>
                            </div>
                        </div>
                    <?php endif ?>


                </div>
            </div>
        </div>
    </div>
</div>


