<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.12.17
 * Time: 23:36
 */

namespace app\core\other\traits;


trait ChangeActive
{
    /**
     * @param int $id
     * @param int|null $status
     */
    public function changeActive(int $id, int $status = null)
    {
        if ($item = self::findOne($id)) {
            !empty($status) ? $item->active = 0 : $item->active = 1;
            $item->save();
        };
    }
}