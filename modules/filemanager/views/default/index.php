<?php

use yii\grid\GridView;
use yii\helpers\Html;
use \app\modules\filemanager\models\Directory;

/** @var \yii\data\ArrayDataProvider $dataProvider */
/** @var Directory $directory */

$a = Yii::t('app', 'File manager');
$this->title = Yii::t('filemanager', 'File manager');

if (!isset($this->params['breadcrumbs'])) {
    $this->params['breadcrumbs'] = [];
}

if ($directory->isRoot) {
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'], $directory->breadcrumbs);
    $this->title .= ' ' . $directory->name;
}

?>
<div class="simple-filemanager">
    <p>
        <?= Html::a('<i class="fa fa-folder fa-fw"></i> ' . Yii::t('filemanager', 'Create directory'),
            ['directory/create', 'path' => $directory->path],
            [
                'class' => 'btn btn-success',
                'data' => [
                    'method' => 'post'
                ]
            ]) ?>
        <?= Html::a('<i class="fa fa-upload fa-fw"></i> ' . Yii::t('filemanager', 'Upload files'),
            ['file/upload', 'path' => $directory->path],
            ['class' => 'btn btn-primary'])
        ?>
    </p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <?php try {
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'value' => function ($item) {
                                    $fa = Html::tag('i', '',
                                        ['class' => 'fa ' . $item['icon'] . ' fa-fw']);
                                    return Html::a($fa . ' ' . $item['name'],
                                        $item['type'] != 'directory' ? $item['url'] : ['index', 'path' => $item['path']]);
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'link',
                                'value' => function ($item) {
                                    return Html::tag('code',
                                        $item['type'] != 'directory' ? $item['url'] : '');
                                },
                                'format' => 'html'
                            ],
                            'time:datetime',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['class' => 'col-xs-1'],
                                'urlCreator' => function ($action, $item) {
                                    return [
                                        $item['type'] . '/' . $action,
                                        'path' => $item['path']
                                    ];
                                },
                                'buttonOptions' => [
                                    'data' => [
                                        'method' => 'post',
                                    ]
                                ],
                                'visibleButtons' => [
                                    'update' => function ($item) {
                                        return $item['type'] == 'directory' && !(empty($item['time']));
                                    },
                                    'delete' =>  function ($item) {
                                        return !(empty($item['time']));
                                    },
                                ],
                                'template' => '{delete} {update}'
                            ],
                        ],
                    ]);
                } catch (Exception $e) {
                    yii::$app->session->setFlash('error', $e->getMessage());
                } ?>
            </div>
        </div>


    </div>
</div>
