<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.12.17
 * Time: 6:21
 */

namespace app\modules\admin\controllers;

use app\components\helpers\FirstErrors;
use app\core\products\ProductImageGallery;
use app\core\products\ProductService;
use app\core\user\services\CheckCan;
use app\modules\admin\controllers\traits\ControllerTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\core\products\ProductSearch;
use app\components\widgets\setPaginationNum\PaginationTrait;

class ProductController extends BaseAdminController
{
    use ControllerTrait;
    use PaginationTrait;

    const PAGINATION_NAME = 'product_pagination';

    private $service;
    private $search;
    private $gallery;

    /**
     * ProductController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param ProductService $service
     * @param ProductSearch $search
     * @param ProductImageGallery $gallery
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, ProductService $service, ProductSearch $search, ProductImageGallery $gallery)
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
                'change-status' => ['post'],
                'change-pagination' => ['post'],
            ],
        ];
        return $arr;
    }


    /**
     * @param int|null $category_id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex(int $category_id = null)
    {
        if ($category_id) {
            $category = $this->service->getCategory($category_id);
            $searchModel = $this->search;
            $pagination = $this->setPagination();
            $dataProvider = $searchModel->search(yii::$app->request->queryParams, $category_id, $pagination);
        } else {
            $category = null;
            $searchModel = null;
            $dataProvider = null;
            $pagination = null;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'parents' => $this->service->getLeavesCategories(),
            'product' =>  $this->service->index($category_id),
            'pagination' => $pagination
        ]);
    }

    /**
     * @param int $category_id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCreate(int $category_id)
    {
        $formModel = $this->service->getNewForm($category_id);

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $id = $this->service->create($formModel);
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
                yii::$app->session->setFlash('error', nl2br($e->getMessage()));
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