<?php

/* @var $this yii\web\View */
/* @var $model \app\modules\filemanager\models\DirectoryForm */
/* @var $directory \app\modules\filemanager\models\Directory */

$this->title = Yii::t('filemanager', 'Rename directory');

if (!isset($this->params['breadcrumbs'])) {
    $this->params['breadcrumbs'] = [];
}

$this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'], $directory->getBreadcrumbs(false));
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', ['model' => $model]) ?>