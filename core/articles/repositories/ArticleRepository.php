<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:11
 */

namespace app\core\articles\repositories;

use app\core\articles\forms\ArticleForm;
use app\core\base\BaseRepository;
use app\core\categories\CategoryRepository;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\ChangeActive;
use app\core\other\traits\Sort;
use app\core\other\traits\UpdateOneField;
use app\core\workWithFiles\helpers\GetWebDir;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property int $categories_id
 * @property string $image
 * @property string $metaDescription
 * @property string $metaTitle
 * @property string $short_text
 * @property string $text
 * @property int $active
 * @property int $sort
 * @property int $created_at
 * @property int $updated_at
 *
 * @property CategoryRepository $category
 * @property ArticleImagesRepository[] $images
 */
class ArticleRepository extends BaseRepository
{
    use Sort;
    use ChangeActive;
    use UpdateOneField;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::className()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @param ArticleForm $form
     * @param bool $sort
     */
    public function insertValues($form, bool $sort = false)
    {
        if ($sort) {
            $form->sort = $this->getNumLastElement(['categories_id' => $form->categories_id], 'sort');
        }
        InsertValuesHelper::insertValues($this, $form, [
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CategoryRepository::className(), ['id' => 'categories_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ArticleImagesRepository::className(), ['articles_id' => 'id']);
    }

    /**
     * @param $category_id
     * @return CategoryRepository
     */
    public static function getCategoryForId($category_id)
    {
        return CategoryRepository::findOne($category_id);
    }

    /**
     * @return string
     */
    public function getWebDir()
    {
        return GetWebDir::getWebDir([$this->category->type_category, $this->category->id, $this->id]);
    }

}