<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 8:41
 */

namespace app\core\categories;

use app\core\base\BaseRepository;
use app\core\interfaces\Repository;
use ale10257\ext\ChangeTreeBehavior;
use app\core\articles\repositories\ArticleRepository as Article;
use app\core\other\activeQuery\MenuQuery;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\UpdateOneField;
use app\core\workWithFiles\helpers\GetWebDir;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\behaviors\TimestampBehavior;
use app\core\other\traits\CheckUniqAliasInTree;
use app\core\products\repositories\ProductRepository as Product;

/**
 * This is the formModel class for table "categories".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $icon
 * @property string $type_category
 * @property boolean $active
 * @property boolean $multiple
 * @property string $image
 * @property string $metaDescription
 * @property string $metaTitle
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $created_at
 * @property integer $updated_at
 * @property Product[] $products
 * @property Article[] $articles
 *
 * @method NestedSetsBehavior parents($level = null)
 * @method NestedSetsBehavior children($level = null)
 * @method NestedSetsBehavior prependTo($parent)
 * @method NestedSetsBehavior appendTo($parent)
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
 * @method UpdateOneField updateField($field)
 * @method CheckUniqAliasInTree checkUniqAlias(string $alias = null, int $tree = null);
 */
class CategoryRepository extends BaseRepository implements Repository
{
    use UpdateOneField;
    use CheckUniqAliasInTree;

    const RESERVED_TYPE_PRODUCT = 'product';
    const RESERVED_TYPE_ARTICLE = 'article';
    const RESERVED_TYPE_NO = 'no_type';

    const RESERVED_ALIAS_PRODUCT = 'products';
    const RESERVED_ALIAS_ARTICLE = 'articles';
    const RESERVED_ALIAS_NO = 'no_type';

    const TYPE_CATEGORY = [
        self::RESERVED_TYPE_ARTICLE => [
            'name' => 'Статьи',
            'alias' => self::RESERVED_ALIAS_ARTICLE,
            'type' => self::RESERVED_TYPE_ARTICLE,
        ],
        self::RESERVED_TYPE_PRODUCT => [
            'name' => 'Продукты',
            'alias' => self::RESERVED_ALIAS_PRODUCT,
            'type' => self::RESERVED_TYPE_PRODUCT,
        ],
        self::RESERVED_TYPE_NO => [
            'name' => 'Нет типа',
            'alias' => self::RESERVED_ALIAS_NO,
            'type' => self::RESERVED_TYPE_NO,
        ],
    ];

    /** @var CategoryRepository[] */
    public $parents_array;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
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
                'class' => TimestampBehavior::className()
            ],
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
     * @param CategoryForm $form
     */
    public function insertValues($form)
    {
        InsertValuesHelper::insertValues($this, $form, [
            'name',
            'alias',
            'icon',
            'type_category',
            'active',
            'multiple',
            'metaDescription',
            'metaTitle',
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
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['categories_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['categories_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getWebDir()
    {
        return GetWebDir::getWebDir([$this->type_category, $this->id]);
    }

    public static function createRoot(): void
    {
        $root = new self();
        $root->name = SITE_ROOT_NAME;
        $root->alias = SITE_ROOT_NAME;
        $root->type_category = self::RESERVED_TYPE_NO;
        $root->makeRoot();

        foreach (self::TYPE_CATEGORY as $key => $item) {
            if ($key != self::RESERVED_TYPE_NO) {
                $parent = new self();
                $parent->name = $item['name'];
                $parent->alias = $item['alias'];;
                $parent->type_category = $key;
                $parent->prependTo($root);
            }
        }
    }

}