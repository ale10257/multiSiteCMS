<?php

namespace app\components\helpers;
use yii;

class FilemtimeHelper
{
    /**
     * @param $file
     * @return bool|int
     */
    public static function getLastTime ($file) {
        $a = filemtime(yii::getAlias('@webroot') . $file);
        return filemtime(yii::getAlias('@webroot') . $file);
    }
}
