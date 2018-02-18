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
        $sort = static::find()->where($where)->max($field);
        return !$sort ? 1 : ++$sort;
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

        $data = $this->getWhereSort($newData->$field, $oldData->$field, $field);

        static::updateAllCounters(
            [$field => $data['count']],
            ['and', ['=', $whereField, $whereFieldValue], $data['where']]
        );

        $oldData->$field = $newData->$field;
        $oldData->save();
    }

    /**
     * @param int $newSortData
     * @param int $oldSortData
     * @param string $field
     * @return array
     */
    public function getWhereSort(int $newSortData, int $oldSortData, string $field)
    {
        if ($oldSortData < $newSortData) {
            //down move
            $count = -1;
            $where = ['and', ['>', $field, $oldSortData], ['<=', $field, $newSortData]];
        } else {
            //up move
            $count = 1;
            $where = ['and', ['>=', $field, $newSortData], ['<', $field, $oldSortData]];
        }

        return [
            'count' => $count,
            'where' => $where
        ];
    }

    /**
     * @param string $field
     * @param int $fieldValue
     * @param string $whereField
     * @param int $whereFieldValue
     */
    public function deleteSortItem(string $field, int $fieldValue, string $whereField, int $whereFieldValue)
    {
        static::updateAllCounters(
            [$field => -1],
            ['and', ['>', $field, $fieldValue], ['=', $whereField, $whereFieldValue] ]
        );
    }
}