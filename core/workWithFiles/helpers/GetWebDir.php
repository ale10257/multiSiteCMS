<?php

namespace app\core\workWithFiles\helpers;

/**
 * Class GetWebDir
 * @package app\core\workWithFiles
 * Helper for create path to web directory
 */
class GetWebDir
{
    /**
     * @param array $arrayItem
     * @return string
     */
    public static function getWebDir(array $arrayItem)
    {
        return DIRECTORY_SEPARATOR . UPLOAD_DIR . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $arrayItem) . DIRECTORY_SEPARATOR;
    }
}