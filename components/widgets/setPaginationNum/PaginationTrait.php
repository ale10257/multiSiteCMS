<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.02.18
 * Time: 8:28
 */

namespace app\components\widgets\setPaginationNum;

use Yii;

trait PaginationTrait
{
    /**
     * @return int
     */
    public function setPagination()
    {
        return yii::$app->session->get(self::PAGINATION_NAME) ?  : 20;
    }

    /**
     * @param int $pagination
     */
    public function actionChangePagination($pagination)
    {
        if ((int)$pagination) {
            yii::$app->session->set(self::PAGINATION_NAME, $pagination);
        }
    }
}