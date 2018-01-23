<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 5:09
 */

namespace app\core\products\forms;

use app\core\other\helpers\InsertValuesHelper;
use app\core\products\repositories\ProductImagesRepository;
use yii\base\Model;

class ProductImageForm extends Model
{
    /** @var int */
    public $id;
    /** @var int */
    public $products_id;
    /** @var string */
    public $name;
    /** @var string */
    public $title_link;
    /** @var string */
    public $alt;
    /** @var int */
    public $sort;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alt', 'title_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alt' => 'Атрибут alt',
            'title_link' => 'Атрибут title для ссылки',
        ];
    }

    /**
     * @param ProductImagesRepository $repository
     */
    public function createUpdateForm(ProductImagesRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'id',
            'alt',
            'name',
            'products_id',
            'title_link',
            'sort',
        ]);
    }
}