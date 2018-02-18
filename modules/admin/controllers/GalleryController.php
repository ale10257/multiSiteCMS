<?php
namespace app\modules\admin\controllers;

use app\components\helpers\FirstErrors;
use app\core\galleries\GalleryImage;
use app\core\galleries\GallerySearch;
use app\core\galleries\GalleryService;
use app\core\user\services\CheckCan;
use app\modules\admin\controllers\traits\ControllerTrait;
use Yii;
use yii\filters\VerbFilter;


class GalleryController extends BaseAdminController
{
    use ControllerTrait;

    /** @var GalleryService */
    private $service;
    /** @var GallerySearch */
    private $search;
    /** @var GalleryImage */
    private $gallery;

    /**
     * GalleryController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param GalleryService $service
     * @param GallerySearch $search
     * @param GalleryImage $gallery
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, GalleryService $service, GallerySearch $search, GalleryImage $gallery)
    {
        parent::__construct($id, $module, $checkCan);
        $this->service = $service;
        $this->search = $search;
        $this->gallery = $gallery;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $arr = parent::behaviors();
        $arr['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
                'delete-image' => ['post'],
            ],
        ];
        return $arr;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->service->index();
        $searchModel = $this->search;
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'formModel' => $this->service->getNewForm()
        ]);
    }

    /**
     * @return \yii\web\Response
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
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }
        return $this->goHome();
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
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('update', ['formModel' => $formModel]);
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