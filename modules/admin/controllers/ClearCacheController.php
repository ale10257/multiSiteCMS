<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.10.17
 * Time: 9:15
 */

namespace app\modules\admin\controllers;

use yii;
use yii\helpers\FileHelper;

class ClearCacheController extends BaseAdminController
{
    private $success;
    private $error;

    /**
     * @return yii\web\Response
     */
    public function actionDeleteCache()
    {
        if (yii::$app->cache->flush()) {
            $this->success = 'Кеш очищен успешно';
        } else {
            $this->error = 'Ошибка при очистке кеша';
        }
        return $this->setMsg();
    }

    /**
     * @return yii\web\Response
     * @throws yii\base\ErrorException
     * @throws yii\base\Exception
     */
    public function actionDeleteAssets()
    {
        $dir = yii::getAlias('@webroot/assets');
        FileHelper::removeDirectory($dir);
        if (FileHelper::createDirectory($dir)) {
            $this->success = 'Assets сброшены успешно';
        } else {
            throw new \DomainException('Directory Assets not created');

        }
        return $this->setMsg();
    }

    /**
     * @return yii\web\Response
     */
    private function setMsg()
    {
        $session = yii::$app->session;
        if ($this->success) {
            $session->setFlash('success', $this->success);
        }
        if ($this->error) {
            $session->setFlash('error', $this->error);
        }
        return $this->redirect(yii::$app->request->referrer);
    }
}
