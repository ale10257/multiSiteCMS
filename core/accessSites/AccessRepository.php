<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\accessSites;

use app\core\base\BaseRepository;
use app\core\other\helpers\InsertValuesHelper;
use app\core\user\entities\user\User;

/**
 * @property int $id
 * @property int $users_id
 * @property string $site_constant
 *
 * @property User $user
 */
class AccessRepository extends BaseRepository
{
    /**
     *@inheritdoc
     */
    public static function tableName()
    {
        return 'access_sites';
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
     * @param AccessForm $form
     * @return void
     */
    public function insertValues($form)
    {
        if (!$form->site_constant) {
            $form->site_constant = SITE_ROOT_NAME;
        }
        InsertValuesHelper::insertValues($this, $form, [
            'users_id',
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


    /**
     * @return User[]|array|\yii\db\ActiveRecord[]
     */
    public static function getAdminUsers()
    {
        return User::find()
            ->where(['<>', 'role', User::RESERVED_ROLES['root']])
            ->andWhere(['<>', 'role', User::RESERVED_ROLES['reg_user']])
            ->andWhere(['<>', 'role', User::RESERVED_ROLES['no_reg']])
            ->orderBy(['first_name' => SORT_ASC])
            ->all();
    }
}