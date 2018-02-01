<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.12.17
 * Time: 8:24
 */

namespace app\modules\admin\controllers;


use app\components\helpers\FirstErrors;
use app\core\settings\SettingService;
use app\core\user\services\CheckCan;
use Yii;
use yii\filters\VerbFilter;

class SettingController extends BaseAdminController
{
    /**
     * @var SettingService
     */
    private $service;
    private $title_form_create = 'Создать настройку';
    private $title_form_update = 'Редактировать настройку ';

    /**
     * SettingController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, SettingService $setting)
    {
        parent::__construct($id, $module, $checkCan);
        $this->service = $setting;
    }

    public function behaviors()
    {
        return [
            [
                'class' => VerbFilter::className(),
                'actions' => ['delete' => ['post']],
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'data' => $this->service->getTree(),
            'form' => null,
        ]);
    }

    /**
     * @param int|null $parent_id
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $parent_id = null)
    {
        try {
            $formModel = $this->service->getForm($parent_id);
        } catch (\Exception $e) {
            $this->session->setFlash('error', $e->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->service->create($formModel, $parent_id);
                $this->session->setFlash('success', 'Данные сохранены успешно!');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        $form = $this->renderPartial('_form', [
            'formModel' => $formModel,
            'title' => $this->title_form_create,
        ]);

        return $this->render('index', ['data' => $this->service->getTree(), 'form' => $form]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        try {
            $formModel = $this->service->getForm(-1, $id);
        } catch (\Exception $e) {
            $this->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->service->update($formModel, $id);
                $this->session->setFlash('success', 'Данные сохранены успешно!');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage() );
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        $form = $this->renderPartial('_form', [
            'formModel' => $formModel,
            'title' => $this->title_form_update . $formModel->name,
        ]);

        return $this->render('index', ['data' => $this->service->getTree(), 'form' => $form]);
    }

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

        return $this->redirect(['index']);
    }

    /**
     * @return string
     */
    public function actionUpdateTree()
    {
        $post = yii::$app->request->post();
        return $this->renderPartial('tree', ['data' => $this->service->updateTree($post)]);
    }
}