<?php

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \app\core\discounts\DiscountSearch */

/** @var $formModel \app\core\discounts\DiscountForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Скидки';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<div class="row">
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['create']),
    ]); ?>
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">Добавить скидку</h2>
            </div>
            <div class="box-body">
                <?= $form->field($formModel, 'start_sum') ?>
                <?= $form->field($formModel, 'percent') ?>
                <div class="form-group text-right">
                    <p><?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></p>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">
                <?php try {
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            'start_sum:integer:От:',
                            'percent:integer:Процент',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}'
                            ],
                        ],
                    ]);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                ?>
            </div>
        </div>
    </div>
</div>
