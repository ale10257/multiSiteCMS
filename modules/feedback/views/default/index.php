<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $formModel \app\core\feedback\FeedBackForm
 * @var $file boolean
 * @var $action string
 */
$file = isset($file) ?: false;
$presentation = isset($presentation) ?: false;
?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'my-feedback',
        'action' => !empty($action) ? $action : '/feedback/',
        'options' => ['enctype' => 'multipart/form-data']
    ]
)
?>

<?= $form->field($formModel, 'name') ?>

<?= $form->field($formModel, 'email') ?>

<?= $form->field($formModel, 'phone') ?>

<?= $form->field($formModel, 'text')->textarea(['rows' => 5]) ?>

<?php if ($presentation) : ?>
    <?= $form->field($formModel, 'presentation')->checkbox() ?>
<?php endif ?>

<?php if ($file) : ?>
    <?= $form->field($formModel,
        'file[]')->fileInput(['multiple' => true,])->label('Прикрепить ваш эскиз (файлы .jpg, .png, .pdf)') ?>
<?php endif ?>

<? if ($formModel->site_key) : ?>
    <?= $form->field($formModel, 'reCaptcha')->widget(
        \himiklab\yii2\recaptcha\ReCaptcha::className(),
        [
            'siteKey' => $formModel->site_key,
            'widgetOptions' => ['id' => 're-captcha-feedback']
        ]
    ) ?>
<? endif ?>

<div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>


