<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.12.17
 * Time: 22:51
 */

namespace app\core\settings;

use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;
use app\core\other\validators\AliasValidator;

class SettingForm extends Model
{
    /** @var string */
    public $name;

    /** @var string */
    public $alias;

    /** @var boolean */
    public $active;

    /** @var string */
    public $icon;

    /** @var string */
    public $value;

    /**@var array */
    public $reserved = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['active'], 'boolean'],
            ['alias', AliasValidator::class],
            [['name', 'alias'], 'string', 'max' => 255],
            [['icon', 'value'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alias' => 'Alias',
            'icon' => 'Fa icon',
            'name' => 'Имя настройки',
            'value' => 'Значение',
            'active' => 'Active',
        ];
    }

    public function createUpdateForm(SettingRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'name',
            'alias',
            'icon',
            'value',
            'active',
        ]);
    }
}