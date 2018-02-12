<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.12.17
 * Time: 15:07
 */

namespace app\core\other\traits;

use app\core\NotFoundException;

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
     * @param \stdClass $newData
     * @param string $field
     * @param string $whereField
     */
    public function changeSort(\stdClass $newData, string $field, string $whereField)
    {
        $oldData = static::findOne($newData->id);

        if (!($oldData && $whereFieldValue = $oldData->$whereField)) {
            throw new NotFoundException('Error sort!');
        }

        if ($oldData->$field < $newData->$field) {
            //down move
            $count = -1;
            $where = '`' . $field . '` > ' . $oldData->$field . ' AND `' . $field . '` <= ' . $newData->$field;
        } else {
            //up move
            $count = 1;
            $where = '`' . $field . '` >= ' . $newData->$field . ' AND `' . $field . '` < ' . $oldData->$field;
        }

        static::updateAllCounters(
            [$field => $count],
            $where  . ' AND `' . $whereField . '` = ' . $whereFieldValue
        );

        $oldData->$field = $newData->$field;
        $oldData->save();
    }
}