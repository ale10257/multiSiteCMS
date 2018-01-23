<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel \app\core\accessSites\AccessForm */

$this->title = 'Досту к сайтам (редактирование)';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи и сайты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', ['formModel' => $formModel, 'update' => true]) ?>
