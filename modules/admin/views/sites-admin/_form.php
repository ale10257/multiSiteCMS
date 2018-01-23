<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31.12.17
 * Time: 10:46
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $formModel \app\core\accessSites\AccessForm */
?>
<div class="row">
    <?php if(empty($update)) : ?>
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['create']),
        ]); ?>
    <?php else : ?>
        <?php $form = ActiveForm::begin(); ?>
    <?php endif ?>
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">Добавить пользвателя</h2>
            </div>
            <div class="box-body">
                <?= $form->field($formModel, 'users_id')->dropDownList($formModel->users_array,
                    ['prompt' => 'Choose user']) ?>
                <?= $form->field($formModel, 'site_constant') ?>
                <div class="form-group text-right">
                    <p><?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></p>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
