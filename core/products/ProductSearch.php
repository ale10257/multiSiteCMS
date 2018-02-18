<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.12.17
 * Time: 21:02
 */

namespace app\core\products;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\core\products\repositories\ProductRepository as Product;

class ProductSearch extends Model
{
    /** @var string */
    public $name;
    /** @var string */
    public $code;
    /** @var boolean */
    public $active;
    /** @var boolean */
    public $new_prod;
    /** @var int */
    public $price;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'string'],
            [[ 'active', 'new_prod'], 'boolean'],
            ['price', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя продукта',
            'code' => 'Артикул',
            'active' => 'Active',
            'new_prod' => 'Новинка',
            'price' => 'Цена',
        ];
    }

    /**
     * @param array $params
     * @param int $category_id
     * @param int $pagination
     * @return ActiveDataProvider
     */
    public function search($params, int $category_id, int $pagination)
    {
        $query = Product::find()
            ->where(['categories_id' => $category_id])
            ->with('images')
            ->with('category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $pagination],
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC,]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'active' => $this->active,
            'new_prod' => $this->new_prod,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}