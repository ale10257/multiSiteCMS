<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 13:13
 */

namespace app\core\other\helpers;

class InsertValuesHelper
{
    /**
     * @param object $inWhichObject
     * @param object $fromWhichObject
     * @param array $data
     * @return object
     */
    public static function insertValues($inWhichObject, $fromWhichObject, $data)
    {
        foreach ($data as $item) {
            $inWhichObject->$item = $fromWhichObject->$item;
        }

        return $inWhichObject;
    }
}
