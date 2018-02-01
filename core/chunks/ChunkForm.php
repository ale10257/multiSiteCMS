<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\chunks;

use app\core\other\helpers\InsertValuesHelper;
use app\core\other\validators\AliasValidator;
use yii\base\Model;

class ChunkForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var string */
    public $alias;
    /** @var string */
    public $description;
    /** @var string */
    public $text;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'text', 'alias'], 'string', 'max' => 255],
            [['alias'], AliasValidator::class],
            [['name', 'alias'], 'unique', 'targetClass' => ChunkRepository::class, 'filter' => $this->id ? ['<>', 'id', $this->id] : null],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя чанка',
            'alias' => 'Alias',
            'description' => 'Описание',
            'text' => 'Содержание',
        ];
    }

    /**
     * @param ChunkRepository $repository
     */
    public function createUpdateForm(ChunkRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'id',
            'name',
            'alias',
            'description',
            'text',
        ]);
    }
}