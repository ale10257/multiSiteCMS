<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\userReg;

use app\core\base\BaseRepository;
use app\core\other\helpers\InsertValuesHelper;
use app\core\user\entities\user\User;

/**
 * @property int $id
 * @property int $users_id
 * @property int $post_code
 * @property string $region
 * @property string $city
 * @property string $address
 * @property string $phone
 * @property string $billing_info
 * @property string $site_constant
 *
 * @property User $user
 */
class UserRegRepository extends BaseRepository
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reg_users';
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @param UserRegForm $form
     * @return void
     */
    public function insertValues($form)
    {
        if (!$form->site_constant) {
            $form->site_constant = SITE_ROOT_NAME;
        }

        InsertValuesHelper::insertValues($this, $form, [
            'users_id',
            'post_code',
            'region',
            'address',
            'city',
            'phone',
            'billing_info',
            'site_constant',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'users_id']);
    }
}