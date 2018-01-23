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
            'urlChangeTree' => Url::to(['/admin/category/update-tree']),
            'urlUpdateTree' => Url::to(['/admin/category/update']),
            'urlDeleteTree' => Url::to(['/admin/category/delete']),
            'urlAddItem' => Url::to(['/admin/category/create']),
            'fieldForTitleItem' => 'name',
        ]
    ]);
} catch (Exception $e) {

}

