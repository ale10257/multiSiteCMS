<?php
/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \app\core\accessSites\AccessSearch */
/** @var $formModel \app\core\accessSites\AccessForm */

use yii\grid\GridView;

$this->title = 'Пользователи и сайты';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>

<?= $this->render('_form', ['formModel' => $formModel]) ?>

<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title"><?= $this->title ?></h2>
            </div>
            <div class="box-body">
                <?php try {
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                               'label' => 'Login',
                               'value' => function ($model) {
                                    /** @var $model \app\core\accessSites\AccessRepository */
                                    return $model->user->login;
                               }
                            ],
                            'site_constant:ntext:SiteConstant',
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
