<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.12.17
 * Time: 8:59
 */

namespace app\core\permissionsAndRoles;

use yii\base\Model;

class FormPermissionsAndRole extends Model
{
    /** @var string */
    public $role;

    /** @var array */
    public $permissions = [];

    /** @var array */
    public $selectedPermissions = [];
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['permissions', 'selectedPermissions'], 'safe']
        ];
    }
}