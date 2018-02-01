<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 7:55
 */

namespace app\modules\admin\controllers\auth;

use app\core\user\auth\ResetPasswordForm;
use app\core\user\entities\user\Identity;
use app\core\user\auth\LoginForm;
use app\core\user\auth\AuthService;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii;
use app\core\user\auth\PasswordResetRequestForm;

class AuthController extends Controller
{
    private $service;

    public $layout = 'main-login';

    public function __construct($id, $module, AuthService $service)
    {
        parent::__construct($id, $module);
        $this->service = $service;
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
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/admin');
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login(new Identity($user), $form->rememberMe ? 3600 * 24 * 30 : 0);
                return $this->redirect('/admin');
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
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $modelForm = new PasswordResetRequestForm();
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            try {
                $this->service->sendEmailResetPassword($modelForm, yii::$app->params['adminEmail']);
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

        $formModel = new ResetPasswordForm();

        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
            try {
                $user = $this->service->resetPassword($formModel, $token);
                Yii::$app->user->login(new Identity($user), 3600 * 24 * 30);
                return $this->redirect(['/admin']);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('resetPassword', [
            'formModel' => $formModel,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
