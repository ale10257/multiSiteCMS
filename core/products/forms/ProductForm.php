<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:12
 */

namespace app\core\products\forms;

use app\core\other\helpers\InsertValuesHelper;
use app\core\other\validators\AliasValidator;
use app\core\products\repositories\ProductRepository;
use app\core\workWithFiles\UploadFiles;
use yii\base\Model;

class ProductForm extends Model
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
    /** @var int */
    public $count;
    /** @var boolean */
    public $active;
    /** @var int */
    public $sort;
    /** @var boolean */
    public $new_prod;
    /** @var string */
    public $description;
    /** @var float */
    public $price;
    /** @var float */
    public $old_price;
    /** @var string */
    public $metaDescription;
    /** @var string */
    public $metaTitle;
    /** @var string */
    public $code;
    /** @var array */
    public $any_images = [];
    /** @var array */
    public $uploaded_images = [];
    /** @var string */
    public $webDir;
    /** @var array */
    public $categories;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'categories_id'], 'required'],
            [['categories_id', 'count', 'active', 'sort', 'new_prod',], 'integer'],
            [['description'], 'string'],
            [['price', 'old_price'], 'number'],
            ['alias', AliasValidator::class],
            [['name', 'alias', 'metaDescription', 'metaTitle'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10],
            [['any_images'], 'image', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 200, 'tooBig' => 'Размер файла не может превышать 200 килобайт', 'maxFiles' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя продукта',
            'alias' => 'Alias',
            'categories_id' => 'Родительская категория',
            'metaDescription' => 'Meta Description',
            'metaTitle' => 'Meta Title',
            'description' => 'Описание',
            'count' => 'Кол-во',
            'price' => 'Цена',
            'old_price' => 'Старая цена',
            'code' => 'Артикул',
            'active' => 'Active',
            'new_prod' => 'Новинка',
            'any_images' => 'Картинки для продукта',
        ];
    }

    public function createUpdateForm(ProductRepository $product)
    {
        InsertValuesHelper::insertValues($this, $product, [
            'name',
            'alias',
            'categories_id',
            'metaDescription',
            'metaTitle',
            'description',
            'count',
            'price',
            'old_price',
            'code',
            'active',
            'new_prod',
            'sort',
        ]);
    }
}