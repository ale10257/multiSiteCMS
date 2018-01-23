<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use app\core\user\entities\user\User;

$roles = yii::$app->authManager->getRoles();

$this->title = 'Роли для админ панели';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">
                <ul>
                    <? foreach ($roles as $role) : ?>
                        <?php if (!array_key_exists($role->name,User::RESERVED_ROLES)) : ?>
                            <li>
                                <?= Html::a($role->name, ['update', 'role' => $role->name]) ?>
                            </li>
                        <?php endif ?>
                    <? endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</div>



