<?php

use vova07\imperavi\Widget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel \app\core\chunks\ChunkForm */

$this->title = 'Чанк  ' . $formModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Чанки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <?= $form->field($formModel, 'name') ?>
                <?= $form->field($formModel, 'alias') ?>
                <?= $form->field($formModel, 'description')->textarea() ?>
                <?= $form->field($formModel, 'text')->widget(Widget::className(), [
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 350,
                        'plugins' => [
                            'table',
                        ],
                    ],
                ]) ?>
                <div class="form-group">
                    <p><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></p>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
