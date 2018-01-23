<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\core\cart\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h2><?= Html::encode($this->title) ?></h2>
            </div>
            <div class="box-body">
                <?php try {
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'rowOptions' => function ($model, $key) {
                            return [
                                'id' => $key
                            ];
                        },
                        'columns' => [
                            'id:integer:№',
                            [
                                'attribute' => 'status',
                                'label' => 'Статус заказа',
                                'filter' => \yii\helpers\ArrayHelper::map($searchModel->status_data, 'status', 'title'),
                                'format' => 'raw',
                                'value' => function ($model) use ($searchModel) {
                                    /**
                                     * @var $searchModel \app\core\cart\OrderSearch
                                     * @var $model \app\core\cart\repositories\OrderRepository
                                     */
                                    $str = '';
                                    $status = $model->status;
                                    foreach ($searchModel->status_data as $key => $item) {
                                        $txt = $item['title'];
                                        $class = $item['class'] . ' status-' . $key;
                                        if ($key != $status) {
                                            $class .= ' hide-btn';
                                        } else {
                                            $class .= ' show-btn';
                                        }
                                        $str .= Html::tag('p', $txt, ['class' => 'btn btn-' . $class,]);
                                    }
                                    return $str;
                                },
                            ],
                            [
                                'attribute' => 'full_name',
                                'label' => 'Фамилия, имя',
                                'format' => 'raw',
                                'value' => /**
                                 * @param $model
                                 * @return bool|string
                                 */
                                    function ($model) {
                                        /**
                                         * @var $model \app\core\cart\repositories\OrderRepository
                                         */

                                        $check_user = null;

                                        $data_user = [
                                            'first_name' => 'No name',
                                            'last_name' => '',
                                            'email' => '',
                                            'phone' => '',
                                        ];

                                        if ($model->status > $model::STATUS_ORDER_CREATION) {
                                            if ($data = json_decode($model->data)) {
                                                $data_user = [
                                                    'first_name' => !empty($data->first_name) ? $data->first_name : $data->firstName,
                                                    'last_name' => !empty($data->last_name) ? $data->last_name : $data->lastName,
                                                    'email' => $data->email,
                                                    'phone' => $data->phone,
                                                ];
                                            }
                                        } else {
                                            if ($model->user->first_name != 'no_name') {
                                                $data_user = [
                                                    'first_name' => $model->user->first_name,
                                                    'last_name' => $model->user->last_name,
                                                    'email' => $model->user->email,
                                                    'phone' => $model->user->role == 'reg_user' ? $model->user->regUser->phone : '',
                                                ];
                                            }
                                        }

                                        $str = Html::tag('p',
                                            $data_user['first_name'] . ' ' . $data_user['last_name']);

                                        if ($data_user['email']) {
                                            $str .= Html::tag('p',
                                                Html::mailto($data_user['email'], $data_user['email'],
                                                    ['target' => '_blank']));
                                        }

                                        if ($data_user['phone']) {
                                            $str .= Html::tag('p', $data_user['phone']);
                                        }

                                        if (!$model->user::findByEmailCount($data_user['email'])) {
                                            $str .= Html::tag('p', Html::tag('strong', 'Не зарегистрирован(а)'));
                                        }

                                        if ($model->ip_address) {
                                            $url = Html::a('Информация Whois',
                                                ['who-is', 'ip' => $model->ip_address], [
                                                    'class' => 'who-is',
                                                    'data-target' => '#who-is',
                                                    'data-toggle' => 'modal',
                                                ]);

                                            $str .= Html::tag('p', $url);
                                        }

                                        return $str;
                                    }

                            ],
                            'updated_at:datetime:Время последнего обращения к заказу',
                            [
                                'attribute' => 'all_sum',
                                'label' => 'Данные о заказе',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    /**
                                     * @var $model \app\core\cart\repositories\OrderRepository
                                     */
                                    $data = $model->dataOrder;
                                    $format = yii::$app->formatter;
                                    $str = Html::tag('p', 'Всего: ' . $format->asInteger($data->sum) . ' р.');
                                    $str .= Html::tag('p',
                                        'Скидка: ' . $format->asInteger($data->discount) . ' р. (' . $data->percent . '%)');
                                    $str .= Html::tag('p', 'Итого: ' . $format->asInteger($data->total) . ' р.');

                                    return $str;
                                }
                            ],
                            [
                                'attribute' => 'change_status_data',
                                'label' => 'Изменить статус',
                                'format' => 'raw',
                                'value' => function ($model) use ($searchModel) {
                                    /**
                                     * @var $model \app\core\cart\repositories\OrderRepository
                                     */

                                    $txt = null;
                                    $change = null;
                                    $class = 'change-status btn btn-';

                                    switch ($model->status) {
                                        case $model::STATUS_ORDER_NOT_VERIFED :
                                            $txt = 'Подтвердить';
                                            $change = $model::STATUS_ORDER_VERIFED;
                                            $class .= $searchModel->status_data[$change]['class'];
                                            break;
                                        case $model::STATUS_ORDER_ERROR_TIMEOUT :
                                            $txt = 'Подтвердить';
                                            $change = $model::STATUS_ORDER_VERIFED;
                                            $class .= $searchModel->status_data[$change]['class'];
                                            break;
                                        case $model::STATUS_ORDER_VERIFED :
                                            $change = $model::STATUS_ORDER_CLOSED;
                                            $txt = 'Отправить';
                                            $class .= $searchModel->status_data[$change]['class'];
                                            break;
                                    }

                                    if ($txt) {
                                        $txt = Html::a($txt,
                                            [
                                                'change-status',
                                                'order_id' => $model->id,
                                                'status' => $change
                                            ],
                                            [
                                                'class' => $class,
                                                'data-confirm' => 'Вы уверены? Статус можно менять только в одну сторону! Обратно не получится!',
                                                'data-method' => 'post'
                                            ]);
                                    }

                                    return $txt;
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {delete}',
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

<?php
Modal::begin([
    'header' => '<h3>Информация Whois</h3>',
    'id' => 'who-is',
]);
echo '<pre id="pre-insert">';
echo '</pre>';
Modal::end();
?>
