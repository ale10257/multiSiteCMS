<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.12.17
 * Time: 22:50
 */

namespace app\core\settings;

use ale10257\ext\ChangeTreeBehavior;
use app\core\base\BaseRepository;
use app\core\other\activeQuery\MenuQuery;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\CheckUniqAliasInTree;
use creocoder\nestedsets\NestedSetsBehavior;


/**
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $icon
 * @property string $value
 * @property boolean $active
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 *
 * @method NestedSetsBehavior parent($level = null)
 * @method NestedSetsBehavior children()
 * @method NestedSetsBehavior prependTo($formModel)
 * @method NestedSetsBehavior deleteWithChildren()
 * @method NestedSetsBehavior makeRoot()
 *
 * @method ChangeTreeBehavior getTree()
 * @method ChangeTreeBehavior getTreeAsArray()
 * @method ChangeTreeBehavior updateTree($data)
 * @method ChangeTreeBehavior createItem($parent)
 * @method ChangeTreeBehavior getRoot()
 * @method ChangeTreeBehavior checkRoot()
 *
 * @method CheckUniqAliasInTree checkUniqAlias(string $alias = null, int $tree = null);
 */

class SettingRepository extends BaseRepository
{
    use CheckUniqAliasInTree;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @return MenuQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }

    /**
     * @param SettingForm $form
     */
    public function insertValues($form) : void
    {
        InsertValuesHelper::insertValues($this, $form, [
            'name',
            'alias',
            'icon',
            'value',
            'active',
        ]);
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
                'rootSite' => SITE_ROOT_NAME,
            ],
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

    public static function createRoot(): void
    {
        $root = new self();
        $root->name = SITE_ROOT_NAME;
        $root->alias = SITE_ROOT_NAME;
        $root->tree = 1;
        $root->makeRoot();

        foreach (ReservedSettings::RESERVED_SETTINGS as $setting) {

            $parent = new self();
            $parent->name = $setting['name'];
            $parent->alias = $setting['alias'];
            $parent->value = $setting['value'];
            $parent->active = $setting['active'];
            $parent->prependTo($root);

            foreach ($setting['childs'] as $item) {
                $child = new self();
                $child->name = $item['name'];
                $child->alias = $item['alias'];
                $child->value = $item['value'];
                $child->active = $item['active'];
                $child->prependTo($parent);
            }
        }
    }
}