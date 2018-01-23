<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\core\articles\repositories\ArticleRepository
 */

use yii\helpers\Html;

/*if (empty($news)) {
    if (!empty($this->context->actionParams['alias']) && $this->context->actionParams['alias'] == 'news') {
        $news = true;
    }
}
$model->createWebDirectories($model);*/
?>

<div class="row">
    <div class="col-md-12">
        <div class="short">
            <h2>
                <?= Html::a($model->name, ['/article/' . $model->alias]) ?>
                <span class="pull-right date-article"><?= yii::$app->formatter->asDate($model->created_at) ?></span>
            </h2>
            <?php if ($img = $model->image) : ?>
                <?php /** @var \app\core\workWithFiles\DataPathImage $img */ ?>
                <?= Html::img($img->webThumbPath, ['class' => 'preview-category']) ?>
            <?php endif ?>
            <?php if ($model->short_text) : ?>
                <?= $model->short_text ?>
            <?php endif ?>
            <p class="clearfix"><?= Html::a('Подробнее', ['/article/' . $model->alias],
                    ['class' => 'more pull-right']) ?></p>
        </div>
        <hr>
    </div>
</div>
