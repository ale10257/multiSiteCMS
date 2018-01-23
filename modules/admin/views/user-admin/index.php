<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\core\user\services\SearchUserModel */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                                'login:ntext:Логин',
                                'first_name:ntext:Имя',
                                'last_name:ntext:Фамилия',
                                'email:email',
                                'role:ntext:Роль',
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
</div>