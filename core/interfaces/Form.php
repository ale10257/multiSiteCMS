<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 8:18
 */

namespace app\core\interfaces;


interface Form
{
    public function createUpdateForm ($repository);
}