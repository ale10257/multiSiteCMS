<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.17
 * Time: 23:59
 */

namespace app\components\helpers;


use yii\base\Model;

class FirstErrors
{
    public static function get(Model $model)
    {
        $errors = null;
        foreach ($model->firstErrors as $key => $item) {
            $errors .= "$key: $item <br>";
        }

        return $errors;
    }
}