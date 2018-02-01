<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.12.17
 * Time: 15:07
 */

namespace app\core\other\traits;


use Yii;

trait Sort
{
    public function getNumLastElement($where, $field)
    {
        if (!$sort = static::find()->where($where)->max($field)) {
            $sort = 1;
        } else {
            $sort++;
        }

        return $sort;
    }

    /**
     * @param array $arr
     * @param string $field
     * @throws \yii\db\Exception
     */
    public function changeSort(array $arr, string $field)
    {
        foreach ($arr as $item) {
            yii::$app->db->createCommand()->update(self::tableName(), [$field => $item->$field], ['id' => $item->id])->execute();
        }
    }

}