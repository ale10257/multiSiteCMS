<?php

namespace app\core\user\services;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\core\user\entities\user\User;

/**
 * SearchUserModel represents the formModel behind the search form about `app\corev2\user\entities\user\User`.
 */
class SearchUserModel extends Model
{
    public $first_name;
    public $last_name;
    public $email;
    public $login;

    public function rules()
    {
        return [
            [['login', 'first_name', 'last_name', 'email',], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email'
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()
            ->where(['<>', 'role', User::RESERVED_ROLES['reg_user']])
            ->andWhere(['<>', 'role', User::RESERVED_ROLES['no_reg']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
