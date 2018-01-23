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
    public static function insertValues($inWhichObject, $fromWhichObject, $data)
    {
        foreach ($data as $item) {
            $inWhichObject->$item = $fromWhichObject->$item;
        }

        return $inWhichObject;
    }
}