<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 8:38
 */

namespace app\core\interfaces;


interface CategoryService
{
    public function index();

    public function create($form, $parent_id);

    public function update($form, int $id);

    public function getNewForm(int $parent_id);

    public function getUpdateForm(int $id);

    public function delete(int $id);
}