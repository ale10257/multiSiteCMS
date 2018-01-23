<?php

namespace app\components\user;

/**
 * @inheritdoc
 *
 * @property \app\core\user\entities\user\Identity|\yii\web\IdentityInterface|null $identity The identity _object associated with the currently logged-in user. Null is returned if the user is not logged in (not authenticated).
 */
class User extends \yii\web\User
{

}
