<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.01.18
 * Time: 7:46
 */

namespace app\core\articles\getArticles;

use app\core\articles\repositories\ArticleRepository;
use app\core\categories\CacheCategory;
use app\core\categories\CategoryRepository;
use app\core\settings\ThumbSettingImg;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ArticleGetService
{
    /** @var CacheCategory */
    private $cacheCategory;
    /** @var ThumbSettingImg */
    private $settingImg;

    /**
     * ArticleGetService constructor.
     * @param CacheCategory $cacheCategory
     * @param ThumbSettingImg $settingImg
     */
    public function __construct(CacheCategory $cacheCategory, ThumbSettingImg $settingImg)
    {
        $this->cacheCategory = $cacheCategory;
        $this->settingImg = $settingImg;
    }

    /**
     * @param $alias
     * @return DataArticle
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function getOneArticle($alias)
    {
        /** @var CategoryRepository[] $categories */
        $categories = ArrayHelper::index($this->cacheCategory->getLeavesCategoryActive('article'), 'alias');
        $query = ArticleRepository::find()->with('category');
        $category = null;
        if (!empty($categories[$alias])) {
            $categories_id = $categories[$alias]->id;
            $query->where(['categories_id' => $categories_id]);
        } else {
            $query->where(['alias' => $alias]);
        }

        $article = $query->andWhere(['active' => 1])->with('images')->one();

        if (!$article) {
            throw new NotFoundHttpException();
        }

        $dataArticle = new DataArticle();
        $dataArticle->metaTitle = $article->metaTitle ? : $article->name;
        $dataArticle->metaDescription = $article->metaDescription ? : null;
        $dataArticle->title = $article->name;
        $dataArticle->articleText = $article->text;

        if ($article->category->multiple) {
            $category = CategoryRepository::findOne(['id' => $article->categories_id]);
        }

        $dataArticle->category = $category;

        $images = [];

        if ($article->images) {
            $imgThumb = $this->settingImg->createImgThumb('preview-gallery', 'thumb-gallery');
            $imgThumb->web_dir = $article->getWebDir();
            foreach ($article->images as $image) {
                if ($img = $imgThumb->checkFile($image->name)) {
                    $images[] = $img;
                }
            }
        }
        $dataArticle->articleGallery = $images;

        return $dataArticle;
    }
}