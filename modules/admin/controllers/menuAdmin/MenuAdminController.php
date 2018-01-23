<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 21:27
 */

namespace app\modules\admin\controllers\menuAdmin;

use app\components\helpers\FirstErrors;
use app\core\user\services\CheckCan;
use app\modules\admin\controllers\BaseAdminController;
use yii\filters\VerbFilter;
use app\core\adminMenu\MenuAdminService;

class MenuAdminController extends BaseAdminController
{
    /**
     * @var MenuAdminService
     */
    private $_menuAdminService;

    /**
     * MenuAdminController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param MenuAdminService $menuAdminService
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, MenuAdminService $menuAdminService)
    {
        parent::__construct($id, $module, $checkCan);
        $this->_menuAdminService = $menuAdminService;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'update-tree' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'data' => $this->_menuAdminService->getTree(),
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionCreate(int $parent_id = null)
    {
        $formModel = $this->_menuAdminService->getNewForm($parent_id);

        if ($formModel->load(\yii::$app->request->post())) {

            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(\yii::$app->request->referrer);
            }

            try {
                $this->_menuAdminService->create($formModel, $parent_id);
                $this->session->setFlash('success', 'Данные сохранены успешно!');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                \yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(\yii::$app->request->referrer);
            }

        }

        return $this->render('index', [
            'form' => $this->renderPartial('_form', [
                'title' => 'Создать разрешение для контроллера админки<br><small>(после создание разрешения необходимо создать сам контроллер)</small>',
                'formModel' => $formModel,
            ]),
            'data' => $this->_menuAdminService->getTree(),
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        try {
            $formModel = $this->_menuAdminService->getEditForm($id);
        } catch (\Exception $e) {
            \yii::$app->session->setFlash('error', $e->getMessage());
            return $this->refresh();
        }

        if ($formModel->load(\yii::$app->request->post())) {

            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(\yii::$app->request->referrer);
            }

            try {
                $this->_menuAdminService->update($formModel, $id);
                $this->session->setFlash('success', 'Данные сохранены успешно!');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                \yii::$app->session->setFlash('error', $e->getMessage());
                return $this->refresh();
            }
        }

        return $this->render('index', [
            'form' => $this->renderPartial('_form', [
                'title' => 'Редактировать разрешение для контроллера админки',
                'formModel' => $formModel,
            ]),
            'data' => $this->_menuAdminService->getTree(),
        ]);
    }

    /**
     * @return string
     */
    public function actionUpdateTree()
    {
        $post = \yii::$app->request->post();
        return $this->renderPartial('tree', ['data' => $this->_menuAdminService->updateTree($post)]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionDelete(int $id)
    {
        try {
            $this->_menuAdminService->delete($id);
            return $this->redirect(['index']);
        } catch (\Exception $e) {
            \yii::$app->session->setFlash('error', $e->getMessage());
            return $this->refresh();
        }
    }
}
