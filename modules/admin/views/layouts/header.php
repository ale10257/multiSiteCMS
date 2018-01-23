<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

/* @var $content string */

?>

<header class="main-header">

    <?= Html::a('<span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo', 'target' => '_blank']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <?//php if (yii::$app->user->can('clear-cache')) : ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs">Настройки</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <?= Html::a('Сбросить кеш', ['/admin/clear-cache/delete-cache']) ?>
                        </li>
                        <li>
                            <?= Html::a('Очистить assests', ['/admin/clear-cache/delete-assets']) ?>
                        </li>
                    </ul>
                </li>
                <?//php endif ?>
                <li><?= Html::a(
                        yii::$app->user->identity->getLogin() . ' (Выход)',
                        ['/admin/auth/logout'],
                        ['data-method' => 'post', 'class' => 'btn']
                    )
                    ?>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <!--<li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>-->
            </ul>
        </div>
    </nav>
</header>
