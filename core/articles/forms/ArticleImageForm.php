<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 10:57
 */

namespace app\core\articles\forms;

use app\core\articles\repositories\ArticleImagesRepository;
use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;

class ArticleImageForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var int */
    public $articles_id;
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
            [['alt', 'title_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alt' => 'Alt',
            'title_link' => 'Title для ссылки',
        ];
    }


    public function createUpdateForm(ArticleImagesRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'id',
            'name',
            'articles_id',
            'alt',
            'title_link',
            'sort',
        ]);
    }
}