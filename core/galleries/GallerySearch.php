<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 13:17
 */

namespace app\core\galleries;


use app\core\galleries\repositories\GalleryRepository;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class GallerySearch extends Model
{
    /** @var string */
    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name',], 'string'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $gallery = new GalleryRepository();

        /** @var GalleryRepository $root */
        $root = $gallery->getRoot();

        $query = GalleryRepository::find()->where(['tree' => $root->tree])->andWhere(['>', 'depth', 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30],
            'sort' => [
                'defaultOrder' => ['lft' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}