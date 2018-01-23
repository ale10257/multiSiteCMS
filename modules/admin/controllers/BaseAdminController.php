<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 20:30
 */

namespace app\modules\admin\controllers;

use app\core\user\services\CheckCan;
use yii;
use yii\web\Controller;
use yii\web\Session;

class BaseAdminController extends Controller
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * DefaultController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan)
    {
        parent::__construct($id, $module);
        $checkCan->checkAdmin($id);
        $this->session = yii::$app->session;
    }
}
