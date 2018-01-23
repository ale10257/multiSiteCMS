<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\userReg;

use app\core\other\helpers\InsertValuesHelper;
use app\core\user\forms\UserAdminCreateForm;
use app\core\user\forms\UserAdminEditForm;
use app\core\user\forms\UserFormInterface;
use elisdn\compositeForm\CompositeForm;

/**
 * Class UserRegForm
 * @package app\core\userReg
 *
 * @property UserAdminCreateForm|UserAdminEditForm $user
 */
class UserRegForm extends CompositeForm
{
    /** @var int */
    public $id;

    /** @var int */
    public $users_id;

    /** @var int */
    public $post_code;

    /** @var string */
    public $region;

    /** @var string */
    public $city;

    /** @var string */
    public $address;

    /** @var string */
    public $phone;

    /** @var string */
    public $billing_info;

    /** @var string */
    public $site_constant;

    public function __construct(UserFormInterface $user, array $config = [])
    {
        parent::__construct($config);
        $this->user = $user;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['users_id', 'post_code'], 'integer'],
            [['phone',], 'required'],
            [['billing_info'], 'string'],
            [['region', 'city', 'address', 'phone', 'site_constant'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_code' => 'Почтовый индекс',
            'region' => 'Регион',
            'city' => 'Город',
            'address' => 'Адрес',
            'phone' => 'Телефон',
            'billing_info' => 'Платежная инф-ция',
        ];
    }

    /**
     * @param UserRegRepository $repository
     */
    public function createUpdateForm(UserRegRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'users_id',
            'post_code',
            'region',
            'address',
            'city',
            'phone',
            'billing_info',
        ]);
    }

    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
       return ['user'];
    }
}