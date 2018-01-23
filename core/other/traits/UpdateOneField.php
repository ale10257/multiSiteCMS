<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.10.17
 * Time: 6:41
 */

namespace app\core\other\traits;
use yii;


trait UpdateOneField
{
    /**
     * @param string $field
     * @throws yii\db\Exception
     */
    public function updateField($field)
    {
        yii::$app->db->createCommand()->update(
            static::tableName(),
            [$field => $this->$field],
            ['id' => $this->id]
        )->execute();
    }
}
