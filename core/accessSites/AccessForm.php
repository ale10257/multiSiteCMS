<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:10
 */

namespace app\core\accessSites;

use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AccessForm extends Model
{
    /** @var int */
    public $users_id;
    /** @var string */
    public $site_constant;
    /** @var array  */
    public $users_array = [];

    /**
     * AccessForm constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->users_array = ArrayHelper::map(AccessRepository::getAdminUsers(), 'id', 'login');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_constant', 'users_id'], 'required'],
            [['site_constant'], 'string'],
            [['users_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'users_id' => 'Пользователь',
            'site_constant' => 'Константа сайта',
        ];
    }

    /**
     * @param AccessRepository $repository
     */
    public function createUpdateForm(AccessRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'users_id',
            'site_constant',
        ]);
    }

}