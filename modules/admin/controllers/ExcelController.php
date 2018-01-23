<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10.01.18
 * Time: 15:52
 */

namespace app\modules\admin\controllers;

use app\core\excel\ExcelBalanceForm;
use app\core\excel\ExcelForm;
use app\core\excel\ExcelService;
use app\core\user\services\CheckCan;
use Yii;

class ExcelController extends BaseAdminController
{
    /** @var ExcelService */
    private $_excelService;

    /**
     * ExcelController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param ExcelService $excelService
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, ExcelService $excelService)
    {
        $this->_excelService = $excelService;
        parent::__construct($id, $module, $checkCan);
    }

    /**
     * @return string|\yii\web\Response|bool
     */
    public function actionIndex()
    {
        $forms = $this->_excelService->getForms();
        /** @var ExcelForm $formModel */
        $formModel = $forms['form'];
        /** @var ExcelBalanceForm $balanceForm */
        $balanceForm = $forms['balanceForm'];

        if ($formModel->load(yii::$app->request->post()) && $formModel->validate()) {
            try {
                if ($this->_excelService->setAction($formModel)) {
                    yii::$app->session->setFlash('success', 'Данные внесены успешно!');
                    return $this->redirect(yii::$app->request->referrer);
                }
                return true;
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        if ($balanceForm->load(yii::$app->request->post()) && $balanceForm->validate()) {
            try {
                $result = $this->_excelService->setAction($balanceForm);
                if (is_array($result)) {
                    if (!$result) {
                        yii::$app->session->setFlash('success', 'Данные внесены успешно!');
                    } else {
                        yii::$app->session->setFlash('error', implode('<br>', $result));
                    }
                    return $this->redirect(yii::$app->request->referrer);
                }
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('index', ['formModel' => $formModel, 'balanceForm' => $balanceForm]);
    }
}