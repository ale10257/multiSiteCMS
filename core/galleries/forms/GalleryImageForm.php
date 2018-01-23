<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 13:15
 */

namespace app\core\galleries\forms;


use app\core\galleries\repositories\GalleryImageRepository;
use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;

class GalleryImageForm extends Model
{
    /** @var int */
    public $id;

    /** @var int */
    public $galleries_id;

    /** @var string */
    public $name;

    /** @var string */
    public $alt;

    /** @var string */
    public $title_link;

    /** @var int */
    public $sort;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['galleries_id'], 'required'],
            [['galleries_id', 'sort'], 'integer'],
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
     * @param GalleryImageRepository $repository
     */
    public function createUpdateForm(GalleryImageRepository $repository)
    {
       InsertValuesHelper::insertValues($this, $repository, [
           'id',
           'galleries_id',
           'name',
           'alt',
           'title_link',
           'sort',
       ]);
    }
}