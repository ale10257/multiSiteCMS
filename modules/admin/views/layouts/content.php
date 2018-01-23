<?php

use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

/**
 * @var  $content string
 */
?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if(isset($this->params['breadcrumbs'])) : ?>
            <?= Breadcrumbs::widget([
                'homeLink' => [
                    'label' => 'Главная',
                    'url' => '/admin'
                ],
                'links' => $this->params['breadcrumbs'],
            ]); ?>
        <?php endif ?>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?= Alert::widget() ?>
            </div>
        </div>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
</footer>

