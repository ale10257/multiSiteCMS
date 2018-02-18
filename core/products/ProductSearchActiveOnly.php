<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 19:43
 */

namespace app\core\products;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\core\products\repositories\ProductRepository as Product;

class ProductSearchActiveOnly extends Model
{
    /** @var int */
    public $price;

    /**
     * @param array $params
     * @param int $category_id
     * @return ActiveDataProvider
     */
    public function search($params, int $category_id)
    {
        $query = Product::find()
            ->where(['categories_id' => $category_id, 'active' => 1])
            ->with('images')
            ->with('category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC,]]
        ]);

        $this->load($params);

        return $dataProvider;
    }
}