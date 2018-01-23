<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 20:53
 */

namespace app\core\accessSites;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class AccessSearch extends Model
{
    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AccessRepository::find()->with(
            ['user' => function ($q) {
                /** @var $q \yii\db\ActiveQuery */
                $q->where(['<>', 'role', 'root'])->andWhere(['<>', 'role', 'reg_user']);
            }]
        );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }

}