<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel \app\core\articles\forms\ArticleForm */

$this->title = 'Обновить статью ' . $formModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'new' => false,
        'formModel' => $formModel,
    ]) ?>

</div>
