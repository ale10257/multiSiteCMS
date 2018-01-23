<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:12
 */

namespace app\core\articles\forms;

use app\core\articles\repositories\ArticleRepository;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\validators\AliasValidator;
use app\core\workWithFiles\UploadFiles;
use yii\base\Model;

class ArticleForm extends Model
{
    use UploadFiles;

    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var string */
    public $alias;
    /** @var int */
    public $categories_id;
    /** @var string */
    public $image;
    /** @var string */
    public $metaDescription;
    /** @var string */
    public $metaTitle;
    /** @var string */
    public $short_text;
    /** @var string */
    public $text;
    /** @var int */
    public $active;
    /** @var int */
    public $sort;
    /** @var string */
    public $one_image;
    /** @var array */
    public $any_images = [];
    /** @var array */
    public $uploaded_images = [];
    /** @var string */
    public $webDir;
    /** @var string */
    public $category_name;
    /** @var array */
    public $categories = [];
    /** @var string */
    public $link;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'categories_id'], 'required'],
            [['sort'], 'integer'],
            [['active'], 'boolean'],
            [['short_text', 'text'], 'string'],
            ['alias', AliasValidator::class],
            [['name', 'alias', 'image', 'metaDescription', 'metaTitle'], 'string', 'max' => 255],
            ['one_image', 'image', 'extensions' => ['jpg', 'png', 'jpeg'], 'maxSize' => 1024 * 200, 'tooBig' => 'Размер файла не может превышать 200 килобайт'],
            [['any_images'], 'image', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 200, 'tooBig' => 'Размер файла не может превышать 200 килобайт', 'maxFiles' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя статьи',
            'alias' => 'Alias',
            'categories_id' => 'Родительская категория',
            'one_image' => 'Картинка для статьи',
            'metaDescription' => 'Meta Description',
            'metaTitle' => 'Meta Title',
            'short_text' => 'Аннотация',
            'text' => 'Текст статьи',
            'active' => 'Active',
        ];
    }

    public function createUpdateForm(ArticleRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'id',
            'name',
            'alias',
            'categories_id',
            'image',
            'metaTitle',
            'metaDescription',
            'short_text',
            'text',
            'active',
            'sort',
        ]);
    }
}