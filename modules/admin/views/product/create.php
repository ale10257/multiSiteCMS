<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $formModel \app\core\products\forms\ProductForm */

$this->title = 'Создать продукт';
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index', 'category_id' => $formModel->categories_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'new' => true,
        'images' => null,
        'formModel' => $formModel,
    ]) ?>

</div>
