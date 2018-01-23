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
            'urlChangeTree' => Url::to(['/admin/setting/update-tree']),
            'urlUpdateTree' => Url::to(['/admin/setting/update']),
            'urlDeleteTree' => Url::to(['/admin/setting/delete']),
            'urlAddItem' => Url::to(['/admin/setting/create']),
            'fieldForTitleItem' => 'name',
        ]
    ]);
} catch (Exception $e) {

}

