<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 10:52
 */

namespace app\core\articles\repositories;

use app\core\base\BaseRepository;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\Sort;
use app\core\workWithFiles\DataPathImage;

/**
 * @property int $id
 * @property string $name
 * @property int $articles_id
 * @property string $alt
 * @property string $title_link
 * @property int $sort
 *
 * @property ArticleRepository $article
 */
class ArticleImagesRepository extends BaseRepository
{
    use Sort;

    /** @var DataPathImage */
    public $imgThumb;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_images';
    }

    /**
     * @param \app\core\articles\forms\ArticleImageForm $form
     */
    public function insertValues($form)
    {
        if (!$form->sort) {
            $form->sort = $this->getNumLastElement(['articles_id' => $form->articles_id], 'sort');
        }
        InsertValuesHelper::insertValues($this, $form, [
            'name',
            'articles_id',
            'alt',
            'title_link',
            'sort',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(ArticleRepository::class, ['id' => 'articles_id']);
    }

    /**
     * @return string
     */
    public function getWebDir()
    {
        return $this->article->getWebDir();
    }

}