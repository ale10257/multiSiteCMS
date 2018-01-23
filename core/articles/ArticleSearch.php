<?php

namespace app\core\articles;

use app\core\categories\CacheCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\core\articles\repositories\ArticleRepository as Article;
use app\core\categories\CategoryRepository as Category;
use yii\helpers\ArrayHelper;

/**
 * ArticleSearch represents the model behind the search form of `app\tmp\Article`.
 */
class ArticleSearch extends Model
{
    /** @var string */
    public $categories_id;

    /** @var boolean */
    public $active;

    /** @var string */
    public $name;
    /**
     * @var CacheCategory
     */
    private $_cacheCategory;

    /**
     * @inheritdoc
     */

    public function __construct(array $config = [], CacheCategory $cacheCategory)
    {
        parent::__construct($config);
        $this->_cacheCategory = $cacheCategory;
    }

    public function rules()
    {
        return [
            [['categories_id', 'active'], 'integer'],
            [['name',], 'string'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $categories = $this->_cacheCategory->getLeavesCategory(Category::RESERVED_TYPE_ARTICLE);

        $query = Article::find()->with('category')->where(['in', 'categories_id', ArrayHelper::map($categories, 'id', 'id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30],
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC,]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'categories_id' => $this->categories_id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}
