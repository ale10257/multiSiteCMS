<?php
/**
 * @var $this \yii\web\View
 * @var $formModel \app\core\permissionsAndRoles\FormPermissionsAndRole;
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$roles = yii::$app->authManager->getRoles();

$this->title = 'Разрешения для роли ' . $formModel->role;
$this->params['breadcrumbs'][] = ['label' => 'Роли и разрешения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">

                <div class="checkbox">
                    <label>
                        <input id="check-all" type="checkbox">
                        Выделить все
                    </label>
                </div>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($formModel, 'selectedPermissions')->checkboxList($formModel->permissions)->label(false) ?>
                <div>
                    <?= $form->field($formModel, 'role')->hiddenInput()->label(false) ?>
                    <?= Html::submitButton('Ok', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Скрыть форму', ['index'], ['class' => 'btn btn-info']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>



