<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.12.17
 * Time: 5:56
 */

namespace app\modules\admin\controllers\traits;

use Yii;

trait ControllerTrait
{
    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        try {
            $this->service->delete($id);
        } catch (\Exception $e) {
            $this->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(yii::$app->request->referrer);
    }

    public function actionChangeActive(int $id, int $status = null)
    {
        $this->service->changeActive($id, $status);
    }

    public function actionSort()
    {
        $this->service->sort(yii::$app->request->post('list'));
    }


    public function actionDeleteImage($id)
    {
        if ($this->gallery->deleteImage($id)) {
            return $this->redirect(yii::$app->request->referrer);
        }
        return false;
    }

    public function actionSortImage()
    {
        $this->gallery->sortImage(yii::$app->request->post('list'));
    }
}