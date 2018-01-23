<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 12:17
 */

namespace app\core\user\services;

use app\components\user\User;
use app\core\accessSites\AccessRepository;
use Yii;
use yii\web\ForbiddenHttpException;

class CheckCan
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $can
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function checkAdmin(string $can) : bool
    {
        if ($this->user->isGuest) {
            yii::$app->errorHandler->errorAction='site/error';
            throw new ForbiddenHttpException();
        }

        if (!$this->user->identity->isRoot()) {
            if (AccessRepository::find()->where(['users_id' => $this->user->id, 'site_constant' => SITE_ROOT_NAME])->count()) {
                if (!$this->user->can($can)) {
                    throw new ForbiddenHttpException();
                }
            } else {
                yii::$app->errorHandler->errorAction='site/error';
                throw new ForbiddenHttpException();
            }
        }
        return true;
    }
}
