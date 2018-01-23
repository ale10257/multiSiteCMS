<?php
/**
 * @var $this \yii\web\View
 * @var $formModel \app\core\userReg\UserRegForm
 * @var string $title
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = $title;
?>

<div class="row ml-15">
    <div class="col-md-6 content person-data-15">

        <h3><?= Html::encode($this->title) ?></h3>

        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('@app/modules/admin/views/user-admin/_innerForm', ['form' => $form, 'formModel' => $formModel->user]) ?>

        <?= $this->render('@app/modules/admin/views/reg-user/_inner_form', ['form' => $form, 'formModel' => $formModel]) ?>

        <?= $this->render('@app/modules/admin/views/user-admin/_role_field', ['form' => $form, 'formModel' => $formModel->user]) ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
