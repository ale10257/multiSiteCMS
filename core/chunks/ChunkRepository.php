<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\chunks;

use app\core\base\BaseRepository;
use app\core\other\helpers\InsertValuesHelper;

/**
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property string $text
 */
class ChunkRepository extends BaseRepository
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chunks';
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
     * @param ChunkForm $form
     * @return void
     */
    public function insertValues($form)
    {
        InsertValuesHelper::insertValues($this, $form, [
            'name',
            'alias',
            'description',
            'text',
        ]);
    }
}