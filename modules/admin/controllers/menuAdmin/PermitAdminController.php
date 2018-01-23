<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.12.17
 * Time: 10:14
 */

namespace app\modules\admin\controllers\menuAdmin;


use app\core\permissionsAndRoles\PermissionsAndRoleService;
use app\core\user\services\CheckCan;
use app\modules\admin\controllers\BaseAdminController;

class PermitAdminController extends BaseAdminController
{
    private $_service;

    /**
     * PermitAdminController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, PermissionsAndRoleService $service)
    {
        parent::__construct($id, $module, $checkCan);
        $this->_service = $service;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $role
     * @return string
     */
    public function actionUpdate($role)
    {
        $formModel = $this->_service->getForm($role);

        if ($formModel->load(\yii::$app->request->post())) {
            try {
                $this->_service->update($formModel);
                $this->session->setFlash('success', 'Данные обновлены успешно!');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect(\yii::$app->request->referrer);
            }
        }

        return $this->render('update', ['formModel' => $formModel]);
    }
}