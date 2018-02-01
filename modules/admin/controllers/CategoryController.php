<?php

namespace app\modules\admin\controllers;

use app\components\helpers\FirstErrors;
use app\core\categories\CategoryService;
use app\core\user\services\CheckCan;
use app\modules\admin\controllers\traits\ControllerTrait;
use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class CategoryController extends BaseAdminController
{
    use ControllerTrait;

    private $title_form_create = 'Создать категорию';
    private $title_form_update = 'Редактировать категорию ';
    private $service;


    /**
     * CategoryController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param CategoryService $service
     * @throws yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, CategoryService $service)
    {
        parent::__construct($id, $module, $checkCan);
        $this->service = $service;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $arr = parent::behaviors();

        $arr['access'] = [
            'class' => AccessControl::className(),
            'only' => ['delete'],
            'rules' => [
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['root', 'admin']
                ],
            ],
        ];
        $arr['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
                'delete-image' => ['post'],
            ],
        ];

        return $arr;
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'data' => $this->service->index(),
            'form' => null,
        ]);
    }

    /**
     * @param int|null $parent_id
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionCreate(int $parent_id = null)
    {
        try {
            $formModel = $this->service->getNewForm($parent_id);
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

        return $this->render('index', ['data' => $this->service->index(), 'form' => $form]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        try {
            $formModel = $this->service->getUpdateForm($id);
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
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        $form = $this->renderPartial('_form', [
            'formModel' => $formModel,
            'title' => $this->title_form_update,
        ]);

        return $this->render('index', ['data' => $this->service->index(), 'form' => $form]);
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
