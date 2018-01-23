<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 8:16
 */

namespace app\core\interfaces;

interface Repository
{
    public function getItem(int $id);

    public function saveItem();

    public function deleteItem();

    public  function insertValues($form);

}