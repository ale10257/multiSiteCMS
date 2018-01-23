<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 8:38
 */

namespace app\core\interfaces;


interface Service
{
    public function create($form);

    public function update($form, int $id);

    public function getNewForm(int $category_id);

    public function getUpdateForm(int $id);

    public function delete(int $id);

    public function changeActive(int $id, int $status = null);
}