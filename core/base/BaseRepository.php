<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 10:02
 */

namespace app\core\base;

use app\core\interfaces\Repository;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class BaseRepository extends ActiveRecord implements Repository
{

    public function insertValues($form)
    {
        // TODO: Implement insertValues() method.
    }

    /**
     * @param int $id
     * @return $this|null
     * @throws NotFoundHttpException
     */
    public function getItem(int $id)
    {
        if (!$object = static::findOne($id)) {
            throw new NotFoundHttpException();
        }
        return $object;
    }

    public function saveItem(): void
    {
        if (!static::save()) {
            throw new \RuntimeException('Saving is error!');
        }
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteItem()
    {
        if (!static::delete()) {
            throw new \RuntimeException('Delete is error!');
        }
    }
}