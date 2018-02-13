<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.02.18
 * Time: 7:35
 */

namespace app\components\widgets\setPaginationNum;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class SetPaginationNumWidget extends Widget
{
    /** @var array */
    public  $arrayPagination = [20 => 20, 30 => 30, 40 => 40, 50 => 50, 60 => 60, 70 => 70, 80 => 80, 90 => 90, 100 => 100];
    /** @var string */
    public $action;
    /** @var int */
    public $pagination;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        yii::$app->view->registerJs('$(function () {
            var action = $("#change-pagination-form").attr("action");
            var select = $("#change-pagination-select");
            select.change(function () {
                $.post(action + "?pagination=" + $(this).val());
                location.reload();
            });
    });');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::beginForm(
            Url::to([$this->action]),
            'post',
            ['id' => 'change-pagination-form']
        );
        echo Html::label('Кол-во элементов на странице');
        echo Html::dropDownList('pagination', $this->pagination, $this->arrayPagination, [
            'id' => 'change-pagination-select',
        ]);
        echo Html::endForm();
    }
}