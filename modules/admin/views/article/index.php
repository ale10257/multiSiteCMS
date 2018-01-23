<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\core\workWithFiles\helpers\GetWebDir;

/* @var $this yii\web\View */
/* @var $searchModel \app\core\articles\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \app\core\articles\repositories\ArticleRepository */
/* @var $parents array */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if ($parents) : ?>
    <div class="article-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php Pjax::begin(); ?>

        <p>
            <?= Html::a('Создать статью', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <?php try {
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    [
                                        'attribute' => 'image',
                                        'label' => 'Картинка',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            /** @var $model \app\core\articles\repositories\ArticleRepository */
                                            if ($model->image) {
                                                return Html::img($model->getWebDir() . $model->image,
                                                    ['style' => 'width: 100%']);
                                            }
                                            return false;
                                        },
                                        'contentOptions' => ['style' => 'width: 150px;']
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'label' => 'Название статьи',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            /** @var $model \app\core\articles\repositories\ArticleRepository */
                                            return Html::a($model->name, Url::to(['update', 'id' => $model->id]), ['data-pjax' => 0]);
                                        }
                                    ],
                                    [
                                        'attribute' => 'categories_id',
                                        'filter' => ArrayHelper::map($parents, 'id', 'name'),
                                        'label' => 'Родительская категория',
                                        'value' => function ($model) {
                                            /** @var $model \app\core\articles\repositories\ArticleRepository */
                                            return $model->category->name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'active',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            /**
                                             * @var $model \app\core\articles\repositories\ArticleRepository
                                             */
                                            if ($model->active) {
                                                $style_active = '';
                                                $style_no_active = ' hide-btn';
                                            } else {
                                                $style_active = ' hide-btn';
                                                $style_no_active = '';
                                            }
                                            $url = Url::to([
                                                '/admin/article/change-active',
                                                'id' => $model->id,
                                                'status' => $model->active
                                            ]);
                                            $active_btn = Html::a('Active', $url,
                                                ['class' => 'btn btn-success btn-active' . $style_active]);
                                            $no_active_btn = Html::a('No active', $url,
                                                ['class' => 'btn btn-danger btn-active' . $style_no_active]);
                                            return $active_btn . $no_active_btn;
                                        },
                                        'filter' => [0 => 'No active', 1 => 'Active']
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} {delete}'
                                    ],
                                ],
                            ]);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>
<?php else : ?>
    <h2>Не создано ни одной категории для статей</h2>
<?php endif ?>
