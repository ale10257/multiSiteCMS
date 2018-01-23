<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $formModel \app\core\articles\forms\ArticleForm */
/* @var $categories_id array */

$this->title = 'Создать статью';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'new' => true,
        'formModel' => $formModel,
    ]) ?>

</div>
