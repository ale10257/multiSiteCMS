<?php

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \app\core\chunks\ChunkSearch */

/** @var $formModel \app\core\chunks\ChunkForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Чанки';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<div class="row">
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['create']),
    ]); ?>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">Добавить чанк</h2>
            </div>
            <div class="box-body">
                <?= $form->field($formModel, 'name') ?>
                <div class="form-group text-right">
                    <p><?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></p>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
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
                            'name:ntext:Имя',
                            'description:ntext:Описание',
                            'alias:ntext:Alias',
                            [
                              'label' => 'Код для вставки чанка',
                              'format' => 'raw',
                              'value' => function ($model) {
                                /** @var $model \app\core\chunks\ChunkRepository */
                                return Html::tag('code',  'chunk_' . $model->id);
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
