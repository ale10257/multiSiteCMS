<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 20:53
 */

namespace app\core\userReg;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserRegSearch extends Model
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;

    public function rules()
    {
        return [
            [['phone', 'email', 'last_name', 'first_name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = UserRegRepository::find()->alias('r')->joinWith(['user u'])->where(['r.site_constant' => SITE_ROOT_NAME]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
            'sort' => [
                'defaultOrder' => [
                    'last_name' => SORT_ASC,
                ],
                'attributes' => [
                    'first_name' => [
                        'asc' => ['u.first_name' => SORT_ASC],
                        'desc' => ['u.first_name' => SORT_DESC],
                    ],
                    'last_name' => [
                        'asc' => ['u.last_name' => SORT_ASC],
                        'desc' => ['u.last_name' => SORT_DESC],
                    ],
                    'email' => [
                        'asc' => ['u.email' => SORT_ASC],
                        'desc' => ['u.email' => SORT_DESC],
                    ]
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'r.phone', $this->phone]);
        $query->andFilterWhere(['like', 'u.email', $this->email]);
        $query->andFilterWhere(['like', 'u.first_name', $this->first_name]);
        $query->andFilterWhere(['like', 'u.last_name', $this->last_name]);

        return $dataProvider;
    }

}