<?php

namespace app\core\adminMenu;

use app\core\base\BaseRepository;
use app\core\other\activeQuery\MenuQuery;
use ale10257\ext\ChangeTreeBehavior;
use app\core\other\helpers\InsertValuesHelper;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the formModel class for table "menu_admin".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $icon
 * @property boolean $show_in_sidebar
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 *
 * @method NestedSetsBehavior parent($level = null)
 * @method NestedSetsBehavior children()
 * @method NestedSetsBehavior prependTo($parent)
 * @method NestedSetsBehavior appendTo($parent)
 * @method NestedSetsBehavior deleteWithChildren()
 * @method NestedSetsBehavior makeRoot()
 *
 * @method ChangeTreeBehavior getTree()
 * @method ChangeTreeBehavior updateTree($data)
 * @method ChangeTreeBehavior createItem($parent)
 * @method ChangeTreeBehavior getRoot()
 *
 */
class MenuAdminRepository extends BaseRepository
{
    const ROOT_MENU_ADMIN = 'ROOT_MENU_ADMIN';

    /**
     * @return MenuQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }

    /**
     * @param MenuAdminForm $form
     */
    public function insertValues($form): void
    {
        InsertValuesHelper::insertValues($this, $form, [
            'title',
            'name',
            'icon',
            'show_in_sidebar',
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_admin';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
            [
                'class' => ChangeTreeBehavior::className(),
                'rootSite' => self::ROOT_MENU_ADMIN,
            ]
        ];
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
     * @return $this
     */
    public function createRoot()
    {
        $this->name = self::ROOT_MENU_ADMIN;
        $this->title = 'Нет родителя';
        $this->makeRoot();

        return $this;
    }

}
