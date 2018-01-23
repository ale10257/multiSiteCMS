<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 8:46
 */

namespace app\core\categories;

use app\core\interfaces\Form;
use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;
use app\core\workWithFiles\UploadFiles;
use app\core\other\validators\AliasValidator;

/**
 * Class CategoryForm
 * @package app\core\category
 * @method UploadFiles uploadOneFile(string $web_dir, string $one_image)
 */
class CategoryForm extends Model implements Form
{
    use UploadFiles;

    /**@var int */
    public $id;
    /**@var string */
    public $name;
    /**@var string */
    public $alias;
    /**@var boolean */
    public $active;
    /**@var boolean */
    public $multiple;
    /**@var string */
    public $image;
    /**@var string */
    public $metaDescription;
    /**@var string */
    public $metaTitle;
    /**@var string */
    public $icon;
    /**@var string */
    public $one_image;
    /**@var string */
    public $parent;
    /**@var string */
    public $type_category;
    /**@var array */
    public $type_category_array = [];
    /**@var string */
    public $name_type_category;
    /**@var string */
    public $web_img;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type_category',], 'required'],
            [['active', 'multiple'], 'boolean'],
            [['name', 'alias', 'image', 'metaDescription', 'metaTitle'], 'string', 'max' => 255],
            ['alias', AliasValidator::class],
            ['alias', 'string', 'min' => 3],
            [['icon'], 'string', 'max' => 100],
            [['type_category', 'name_type_category'], 'string', 'max' => 32],
            [
                'one_image',
                'image',
                'extensions' => ['jpg', 'png'],
                'maxSize' => 1024 * 200,
                'tooBig' => 'Размер файла не может превышать 200 килобайт'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя категории',
            'alias' => 'Alias',
            'icon' => 'Fa icon',
            'name_type_category' => 'Тип категории',
            'active' => 'Active',
            'multiple' => 'Отметьте, если у категории будет больше, чем одна статья',
            'image' => 'Картинка категории',
            'metaDescription' => 'Meta Description',
            'metaTitle' => 'Meta Title',
            'parent' => 'Родительская категория',
            'one_image' => 'Картинка для категории',
        ];
    }

    /**
     * @param CategoryRepository $repository
     */
    public function createUpdateForm($repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'id',
            'name',
            'alias',
            'icon',
            'type_category',
            'active',
            'multiple',
            'metaDescription',
            'metaTitle',
            'image',
        ]);
    }
}