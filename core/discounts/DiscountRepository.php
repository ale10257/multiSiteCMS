<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\discounts;

use app\core\base\BaseRepository;
use app\core\other\helpers\InsertValuesHelper;

/**
 * @property int $id
 * @property int $start_sum
 * @property int $percent
 * @property string $site_constant
 */
class DiscountRepository extends BaseRepository
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discounts';
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @param DiscountForm $form
     * @return void
     */
    public function insertValues($form)
    {
        if (!$form->site_constant) {
            $form->site_constant = SITE_ROOT_NAME;
        }
        InsertValuesHelper::insertValues($this, $form, [
            'start_sum',
            'percent',
            'site_constant',
        ]);
    }

}