<?php

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \app\core\galleries\GallerySearch */
/** @var $formModel \app\core\galleries\forms\GalleryForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Галереи сайта';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>

<div class="row">
    <?php $form = ActiveForm::begin([
            'action' => Url::to(['create']),
            'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">Добавить галерею</h2>
            </div>
            <div class="box-body">
                <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
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
                            [
                                'attribute' => 'name',
                                'label' => 'Название галереи',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    /** @var $model \app\core\articles\repositories\ArticleRepository */
                                    return Html::a($model->name, Url::to(['update', 'id' => $model->id]));
                                }
                            ],
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
