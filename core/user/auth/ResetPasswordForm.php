<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.01.18
 * Time: 19:27
 */

namespace app\core\user\auth;

use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
}