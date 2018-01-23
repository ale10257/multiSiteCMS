<?php
use ale10257\ext\GetTreeWidget;
use yii\helpers\Url;

/**
 *@var  $data array
 */

try {
    echo GetTreeWidget::widget([
        'options' => [
            'data' => $data,
            'urlChangeTree' => Url::to(['/admin/menu-admin/update-tree']),
            'urlUpdateTree' => Url::to(['/admin/menu-admin/update']),
            'urlDeleteTree' => Url::to(['/admin/menu-admin/delete']),
            'urlAddItem' => Url::to(['/admin/menu-admin/create']),
            'fieldForTitleItem' => 'title',
        ]
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}

