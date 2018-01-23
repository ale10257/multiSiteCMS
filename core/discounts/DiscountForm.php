<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\discounts;

use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;

class DiscountForm extends Model
{
    /** @var int */
    public $start_sum;

    /** @var int */
    public $percent;

    /** @var string */
    public $site_constant;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_sum', 'percent'], 'required'],
            [['start_sum', 'percent'], 'integer'],
            [['site_constant'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'start_sum' => 'От:',
            'percent' => 'Процент',
        ];
    }

    /**
     * @param DiscountRepository $repository
     */
    public function createUpdateForm(DiscountRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'start_sum',
            'percent'
        ]);
    }
}