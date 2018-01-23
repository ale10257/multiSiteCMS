<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:12
 */

namespace app\core\galleries\forms;

use app\core\galleries\repositories\GalleryRepository;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\validators\AliasValidator;
use app\core\workWithFiles\UploadFiles;
use yii\base\Model;

class GalleryForm extends Model
{
    use UploadFiles;

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $alias;

    /** @var array */
    public $any_images = [];

    /** @var array */
    public $uploaded_images = [];

    /** @var string */
    public $webDir;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'alias'], 'string', 'min'  => 3, 'max' => 255],
            ['alias', AliasValidator::class],
            [['any_images'], 'image', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 200, 'tooBig' => 'Размер файла не может превышать 200 килобайт', 'maxFiles' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alias' => 'Alias',
            'name' => 'Имя галереи',
        ];
    }

    /**
     * @param GalleryRepository $repository
     */
    public function createUpdateForm($repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'id',
            'name',
            'alias',
        ]);
    }
}