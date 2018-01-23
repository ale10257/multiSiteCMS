<?php

use app\core\userReg\UserRegSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $dataProvider ActiveDataProvider */
/** @var $searchModel UserRegSearch */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= $this->title ?></h1>
<p>
    <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
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
                                'label' => 'Имя',
                                'attribute' => 'first_name',
                                'value' => function ($model) {
                                    /** @var \app\core\userReg\UserRegRepository $model */
                                    return $model->user->first_name;
                                }
                            ],
                            [
                                'label' => 'Фамилия',
                                'attribute' => 'last_name',
                                'value' => function ($model) {
                                    /** @var \app\core\userReg\UserRegRepository $model */
                                    return $model->user->last_name;
                                }
                            ],
                            [
                                'label' => 'Email',
                                'attribute' => 'email',
                                'format' => 'email',
                                'value' => function ($model) {
                                    /** @var \app\core\userReg\UserRegRepository $model */
                                    return $model->user->email;
                                }
                            ],
                            'phone:ntext:Телефон',
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
