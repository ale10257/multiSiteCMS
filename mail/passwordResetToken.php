<?php

use yii\helpers\Html;

/**
 * @var $user \app\core\user\entities\user\User
 * @var $url string
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([$url . '/reset-password', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    <p>Здравствуйте <?= Html::encode($user->first_name) ?>,</p>
    <p>Для восстановления пароля перейдите, пожалуйста, по ссылке:</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
