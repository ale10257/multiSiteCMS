<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var $image \app\core\articles\forms\ArticleImageForm
 */

?>

<?php Pjax::begin([
    'enablePushState' => false,
]) ?>
<?php $formImg = ActiveForm::begin([
    'action' => Url::to(['/admin/article/update-image', 'id' => $image->id]),
    'options' => [
        'data' => [
            'pjax' => ''
        ]
    ]
]); ?>
<?= $formImg->field($image, 'alt') ?>
<?= $formImg->field($image, 'title_link') ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
<? Pjax::end() ?>
