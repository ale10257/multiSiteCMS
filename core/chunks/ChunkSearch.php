<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 20:53
 */

namespace app\core\chunks;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class ChunkSearch extends Model
{
    public $name;

    public function search($params)
    {
        $query = ChunkRepository::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);

        return $dataProvider;
    }

}