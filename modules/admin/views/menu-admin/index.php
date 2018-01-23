<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $data array */
/* @var $form string */

$this->title = 'Меню админки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <p>
            <?= Html::a('Создать разрешение', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">
                <?php if(!empty($data)) : ?>
                    <?= $this->render('tree', ['data' => $data]) ?>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php if(!empty($form)) : ?>
        <?= $form ?>
    <?php endif ?>
</div>
