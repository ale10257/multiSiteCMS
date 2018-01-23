<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:11
 */

namespace app\core\galleries\repositories;

use ale10257\ext\ChangeTreeBehavior;
use app\core\base\BaseRepository;
use app\core\galleries\forms\GalleryForm;
use app\core\other\activeQuery\MenuQuery;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\CheckUniqAliasInTree;
use app\core\workWithFiles\helpers\GetWebDir;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "galleries".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 *
 * @property GalleryImageRepository[] $images
 *
 * @method NestedSetsBehavior parents($level = null)
 * @method NestedSetsBehavior children($level = null)
 * @method NestedSetsBehavior prependTo($formModel)
 * @method NestedSetsBehavior appendTo($formModel)
 * @method NestedSetsBehavior deleteWithChildren()
 * @method NestedSetsBehavior makeRoot()
 * @method NestedSetsBehavior leaves()
 *
 * @method ChangeTreeBehavior getTree()
 * @method ChangeTreeBehavior updateTree($data)
 * @method ChangeTreeBehavior createItem($parent)
 * @method ChangeTreeBehavior getRoot()
 * @method ChangeTreeBehavior checkRoot()
 *
 * @method CheckUniqAliasInTree checkUniqAlias(string $alias = null, int $tree = null);
 */
class GalleryRepository extends BaseRepository
{
    use CheckUniqAliasInTree;

    const GALLERY_DIR = 'galleries';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'galleries';
    }

    /**
     * @return MenuQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
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
     * @param GalleryForm $form
     */
    public function insertValues($form)
    {
        InsertValuesHelper::insertValues($this, $form, [
            'name',
            'alias',
        ]);
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
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(GalleryImageRepository::className(), ['galleries_id' => 'id']);
    }

    public function getWebDir()
    {
        return GetWebDir::getWebDir([self::GALLERY_DIR, $this->id]);
    }
}