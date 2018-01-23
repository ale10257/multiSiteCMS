<?php

use yii\helpers\Html;

$root_menu = [
    'menu-admin' => 'Меню для админки',
    'user-admin' => 'Пользователи админки',
    'sites-admin' => 'Пользователи и сайты',
    'permit-admin' => 'Роли и разрешения',
];
?>


<ul class="sidebar-menu tree" data-widget="tree">
    <li class="treeview">
        <a href="#"><i class="fa fa-key"></i>
            <span>SuperUser Root partition</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
            <ul class="treeview-menu">
                <? foreach ($root_menu as $key => $menu) : ?>
                    <?php
                    $active = $this->context->id == $key ? ['class' => 'active'] : [];
                    ?>
                    <?= Html::beginTag('li', $active) ?>
                    <?= Html::a('<i class="fa fa-circle-o"></i><span>' . $menu . '</span>',
                        ['/admin/' . $key], $active) ?>
                    <?= Html::endTag('li') ?>
                <? endforeach ?>
            </ul>
        </a>
    </li>
    <li><a target="_blank" href="/gii"><i class="fa fa-file-code-o"></i> <span>Gii</span></a></li>
    <li><a target="_blank" href="/debug"><i class="fa fa-area-chart"></i> <span>Debug</span></a></li>
</ul>

