<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 20:53
 */

namespace app\core\discounts;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class DiscountSearch extends Model
{
    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DiscountRepository::find()->where(['site_constant' => SITE_ROOT_NAME]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['start_sum' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}