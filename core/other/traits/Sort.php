<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.12.17
 * Time: 15:07
 */

namespace app\core\other\traits;

trait Sort
{
    /**
     * @param $where
     * @param $field
     * @return int
     */
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
     * @param \stdClass $object
     * @param string $field
     * @param string $whereField
     */
    public function changeSort(\stdClass $object, string $field, string $whereField)
    {
        $oldData = static::findOne($object->id);
        $whereFieldValue = $oldData->$whereField;

        if ($oldData->$field < $object->$field) {
            static::updateAllCounters(
                [$field => -1],
                '`' . $field . '` > 1 AND `' . $field . '` <= ' . $object->$field . ' AND `' . $whereField . '` = ' . $whereFieldValue
            );
        } else {
            static::updateAllCounters(
                [$field => 1],
                '`' . $field . '` >= ' . $object->$field . ' AND `' . $field . '` < ' . $oldData->$field  . ' AND `' . $whereField . '` = ' . $whereFieldValue
            );
        }

        $oldData->$field = $object->$field;
        $oldData->save();
    }
}