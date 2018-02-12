<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\admin\assets\AdminSortableAsset;
use app\core\workWithFiles\helpers\GetWebDir;
use app\components\widgets\setPaginationNum\SetPaginationNumWidget;

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var \app\core\categories\CategoryRepository[] $parents */
/** @var \app\core\categories\CategoryRepository $category */
/** @var \app\core\products\ProductSearch $searchModel */
/** @var \app\core\products\forms\ProductForm $product */
/** @var int $pagination */

AdminSortableAsset::register($this);

$this->title = 'Продукты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($parents) : ?>
    <div class="product-index">
        <h2><?= Html::encode($this->title) ?></h2>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-body">
                        <?php
                        $form = ActiveForm::begin([
                            'action' => Url::to(['index'])
                        ]);
                        ?>
                        <?php
                        try {
                            echo $form->field($product, 'categories_id')->widget(Select2::class, [
                                'language' => 'ru',
                                'options' => ['placeholder' => 'Выберите категорию'],
                                'data' => ArrayHelper::map($parents, 'id', 'name'),
                                'pluginEvents' => [
                                    "change" => 'function() {
                                        var form = $(this).parents("form");
                                        location.href = form.attr("action") + "?category_id=" + $(this).val();
                                }',
                                ]
                            ]);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        } ?>
                        <?php $form::end() ?>
                    </div>
                </div>
            </div>
            <?php if ($pagination) : ?>
                <div class="col-md-2 col-md-offset-4">
                    <div class="box">
                        <div class="box-body">
                            <?= SetPaginationNumWidget::widget([
                                'action' => 'change-pagination',
                                'pagination' => $pagination
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

        </div>

        <?php if (!empty($dataProvider)) : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <h3>Продукты для категории <?= $category->name ?></h3>
                            <p><?= Html::a('Сбросить фильтры',
                                    ['index', 'category_id' => $category->id]) ?></p>
                            <p>
                                <?= Html::a('Добавить продукт', ['create', 'category_id' => $category->id],
                                    ['class' => 'btn btn-success']) ?>
                            </p>
                            <?php try {
                                echo GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'tableOptions' => ['class' => 'sortable-table', 'data-url' => Url::to(['sort'])],
                                    'rowOptions' => function ($model) {
                                        return ['data-id' => $model->id, 'class' => 'sortable-tr'];
                                    },
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'attribute' => 'images',
                                            'label' => '',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                /**
                                                 * @var $model \app\core\products\repositories\ProductRepository
                                                 */
                                                if (!empty($model->images[0])) {
                                                    $web_dir = GetWebDir::getWebDir([
                                                        $model->category->type_category,
                                                        $model->category->id,
                                                        $model->id
                                                    ]);
                                                    return Html::img($web_dir . $model->images[0]->name,
                                                        ['style' => 'width: 120px; height: auto;']);
                                                }
                                                return 'Нет картинки';
                                            }
                                        ],
                                        [
                                            'attribute' => 'name',
                                            'label' => 'Имя продукта',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                /**
                                                 * $formModel \app\core\products\repositories\ProductRepository
                                                 */
                                                return Html::a($model->name, ['update', 'id' => $model->id]);
                                            },
                                        ],
                                        'code:ntext:Артикул',
                                        'price:integer:Цена',
                                        [
                                            'attribute' => 'active',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                /**
                                                 * $formModel \app\core\products\repositories\ProductRepository
                                                 */
                                                if ($model->active) {
                                                    $style_active = '';
                                                    $style_no_active = ' hide-btn';
                                                } else {
                                                    $style_active = ' hide-btn';
                                                    $style_no_active = '';
                                                }
                                                $url = Url::to([
                                                    '/admin/product/change-active',
                                                    'id' => $model->id,
                                                    'status' => $model->active
                                                ]);
                                                $active_btn = Html::a('Active', $url, [
                                                    'class' => 'btn btn-success btn-active' . $style_active,
                                                    'data-pjax' => 0
                                                ]);
                                                $no_active_btn = Html::a('No active', $url, [
                                                    'class' => 'btn btn-danger btn-active' . $style_no_active,
                                                    'data-pjax' => 0
                                                ]);
                                                return $active_btn . $no_active_btn;
                                            },
                                            'filter' => [0 => 'No active', 1 => 'Active']
                                        ],
                                        [
                                            'attribute' => 'new_prod',
                                            'filter' => [0 => 'Не новинка', 1 => 'Новинка'],
                                            'format' => 'raw',
                                            'label' => 'Новинка',
                                            'value' => function ($model) {
                                                /**
                                                 * @var $model \app\core\products\repositories\ProductRepository
                                                 */
                                                $str = null;
                                                if ($model->new_prod) {
                                                    $str = Html::tag('button', 'New', ['class' => 'btn btn-danger']);
                                                }
                                                return $str;
                                            }
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{update} {delete}'
                                        ],
                                    ],
                                ]);
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            } ?>

                        </div>
                    </div>
                </div>

            </div>
        <?php endif ?>
    </div>

<?php else : ?>
    <h2>Не создано ни одной категории для продуктов</h2>
<?php endif ?>


