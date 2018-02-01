<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.12.17
 * Time: 18:36
 */

namespace app\modules\admin\controllers;

use app\components\helpers\FirstErrors;
use app\core\articles\ArticleSearch;
use app\core\articles\ArticleService;
use app\core\articles\ArticleImageGallery;
use app\core\user\services\CheckCan;
use app\modules\admin\controllers\traits\ControllerTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ArticleController extends BaseAdminController
{
    use ControllerTrait;

    /** @var ArticleService */
    private $service;
    /** @var ArticleSearch */
    private $search;
    /** @var ArticleImageGallery */
    private $gallery;

    /**
     * ArticleController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param ArticleService $service
     * @param ArticleSearch $search
     * @param ArticleImageGallery $gallery
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, ArticleService $service, ArticleSearch $search, ArticleImageGallery $gallery)
    {
        parent::__construct($id, $module, $checkCan);
        $this->service = $service;
        $this->search = $search;
        $this->gallery = $gallery;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $arr = parent::behaviors();
        $arr['access'] = [
            'class' => AccessControl::className(),
            'only' => ['delete', 'create'],
            'rules' => [
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['root', 'admin']
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['root', 'manager', 'admin']
                ],
            ],
        ];
        $arr['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
                'delete-image' => ['post'],
                'delete-main-image' => ['post'],
                'change-status' => ['post'],
                'update-image' => ['post'],
            ],
        ];
        return $arr;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = $this->search;
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'parents' => $this->service->getLeavesCategories()
        ]);
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $formModel = $this->service->getNewForm();

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $id = $this->service->create($formModel);
                $this->session->setFlash('success', 'Данные сохранены успешно!');
                return $this->redirect(['update', 'id' => $id]);
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('create', ['formModel' => $formModel]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response|string
     */
    public function actionUpdate(int $id)
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
                return $this->redirect(yii::$app->request->referrer);
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('update', ['formModel' => $formModel]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDeleteMainImage($id)
    {
        $this->service->deleteMainImage($id);
        return $this->redirect(yii::$app->request->referrer);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdateImage(int $id)
    {
        $formModel = $this->gallery->getForm($id);

        if ($formModel->load(yii::$app->request->post()) && $formModel->validate()) {
            try {
                $this->gallery->updateImage($formModel, $id);
                return $this->renderPartial('_form_image', ['image' => $this->gallery->getForm($id)]);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        yii::$app->session->setFlash('error', 'Неизвестная ошибка при обновлении формы.');
        return $this->redirect(yii::$app->request->referrer);
    }

}