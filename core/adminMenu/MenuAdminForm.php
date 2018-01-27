<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.12.17
 * Time: 0:28
 */

namespace app\core\adminMenu;

use app\core\interfaces\Form;
use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;

class MenuAdminForm extends Model implements Form
{
    /** @var string */
    public $name;
    /** @var string */
    public $title;
    /** @var string */
    public $icon;
    /** @var string */
    public $parent;
    /** @var string */
    public $description;
    /** @var boolean */
    public $show_in_sidebar;
    /** @var array */
    public $roles = [];
    /** @var array */
    public $selectedRoles = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'name'], 'required'],
            ['show_in_sidebar', 'boolean'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'match', 'pattern' => '/^[a-z-]+$/', 'message' => 'Поле alias может содержать только строчные символы латинского алфавита и -'],
            [['title', 'icon'], 'string', 'max' => 255],
            [['roles', 'selectedRoles'], 'safe'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя контроллера для роутинга (CamelCase -> camel-case)',
            'title' => 'Имя для меню',
            'icon' => 'fa icon',
            'parent' => 'Родитель',
            'roles' => 'Роли',
            'show_in_sidebar' => 'Показывать в боковом меню',
            'description' => 'Описание',
        ];
    }


    /**
     * @param MenuAdminRepository $repository
     */
    public function createUpdateForm($repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'name',
            'icon',
            'show_in_sidebar',
            'title',
        ]);
    }
}
