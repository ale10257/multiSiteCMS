<?php
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\filemanager\models\DirectoryForm */

?>

<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <div class="directory-form">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'path')->hiddenInput()->label(false) ?>
                    <?php if(!$model->isNew) : ?>
                        <?= $form->field($model, 'oldName')->hiddenInput(['value' => $model->name])->label(false) ?>
                    <?php endif ?>
                    <?= $form->field($model, 'name')->textInput() ?>
                    <div class="form-group">
                        <?= yii\helpers\Html::submitButton(\Yii::t('filemanager', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
