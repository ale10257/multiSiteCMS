<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.01.18
 * Time: 12:56
 */

namespace app\sites\startSite\controllers;

use app\components\helpers\FirstErrors;
use app\core\cart\OrderCheckService;
use app\core\categories\CacheCategory;
use app\core\user\auth\AuthService;
use app\core\user\auth\LoginEmailForm;
use app\core\user\auth\PasswordResetRequestForm;
use app\core\user\auth\ResetPasswordForm;
use app\core\user\entities\user\Identity;
use app\core\userReg\UserRegService;
use app\sites\startSite\BaseController;
use Yii;
use yii\filters\VerbFilter;

class LoginController extends BaseController
{
    /**
     * @var AuthService
     */
    private $_service;
    /**
     * @var UserRegService
     */
    private $_regService;
    /**
     * @var AuthService
     */

    /**
     * LoginController constructor.
     * @param string $id
     * @param $module
     * @param CacheCategory $cacheCategory
     * @param OrderCheckService $orderCheckService
     * @param AuthService $service
     * @param UserRegService $regService
     */
    public function __construct(
        $id,
        $module,
        CacheCategory $cacheCategory,
        OrderCheckService $orderCheckService,
        AuthService $service,
        UserRegService $regService)
    {
        parent::__construct($id, $module, $cacheCategory, $orderCheckService);
        $this->_service = $service;
        $this->_regService = $regService;
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginEmailForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->_service->authRegUser($form);
                Yii::$app->user->login(new Identity($user), $form->rememberMe ? 3600 * 24 * 30 : 0);
                return $this->goHome();
            } catch (\DomainException $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'formModel' => $form,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $formModel = $this->_regService->getNewForm();

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                yii::$app->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $user = $this->_regService->create($formModel);
                Yii::$app->user->login(new Identity($user), 3600 * 24 * 30);
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }
        return $this->render('create', ['formModel' => $formModel, 'title' => 'Регистрация нового пользователя']);
    }

    /**
     * @return \yii\web\Response|string
     */
    public function actionUpdate()
    {
        try {
            $id = $this->_regService->getIdRegUser(yii::$app->user->id);
            $formModel = $this->_regService->getUpdateForm($id);
        } catch (\Exception $e) {
            yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->_session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->_regService->update($formModel, $id);
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('create', ['formModel' => $formModel, 'title' => 'Редактирование личных данных']);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $modelForm = new PasswordResetRequestForm();
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $this->_service->sendEmailResetPassword($modelForm, yii::$app->params['adminEmail'], true);
                yii::$app->session->setFlash('success', 'Проверьте почту, и следуйте инструкциям в письме. Время жизни токена для восстановления пароля - 1 час.');
                return $this->goHome();
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('requestPasswordResetForm', [
            'model' => $modelForm,
        ]);
    }

    /**
     * @param $token
     * @return yii\web\Response|string
     */
    public function actionResetPassword($token) {
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $user = $this->_service->resetPassword($model, $token);
                Yii::$app->user->login(new Identity($user), 3600 * 24 * 30);
                return $this->goHome();
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}