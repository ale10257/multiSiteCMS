<?php
use kartik\select2\Select2;

if ($formModel->role == 'root' || $formModel->role == 'reg_user') {
    echo $form->field($formModel, 'role')->hiddenInput()->label(false);
} else {
    echo $form->field($formModel, 'role')->widget(Select2::classname(), [
        'data' => $formModel->roles,
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите роль'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
}
?>