<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel \app\core\products\forms\ProductForm */
/* @var $parents array */
/* @var $images array */

$this->title = 'Редактировать продукт: ' . $formModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index', 'category_id' => $formModel->categories_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-update">
    <h2><?= Html::encode($this->title) ?></h2>
    <?= $this->render('_form', [
        'formModel' => $formModel,
    ]) ?>
</div>
