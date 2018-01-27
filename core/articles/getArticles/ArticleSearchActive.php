<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.01.18
 * Time: 10:56
 */

namespace app\core\articles\getArticles;

use app\core\articles\repositories\ArticleRepository;
use app\core\categories\CacheCategory;
use app\core\settings\ThumbSettingImg;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ArticleSearchActive extends Model
{
    /** @var CacheCategory  */
    private $_cacheCategory;
    /** @var ThumbSettingImg */
    private $_settingImg;

    /**
     * ArticleSearchActive constructor.
     * @param array $config
     * @param CacheCategory $cacheCategory
     * @param ThumbSettingImg $settingImg
     */
    public function __construct($config = [], CacheCategory $cacheCategory, ThumbSettingImg $settingImg)
    {
        parent::__construct($config);
        $this->_cacheCategory = $cacheCategory;
        $this->_settingImg = $settingImg;
    }

    /**
     * @param $alias
     * @return \app\core\categories\CategoryRepository
     * @throws NotFoundHttpException
     */
    public function getCategory($alias)
    {
        /** @var \app\core\categories\CategoryRepository[] $categories */
        $categories = ArrayHelper::index($this->_cacheCategory->getLeavesCategoryActive('article'), 'alias');

        if (empty($categories[$alias])) {
            throw new NotFoundHttpException();
        }

        return $categories[$alias];
    }

    /**
     * @param int $category_id
     * @return ActiveDataProvider
     * @throws \yii\base\Exception
     */
    public function search(int $category_id)
    {
        $query = ArticleRepository::find()
            ->where(['categories_id' => $category_id])
            ->with('category')
            ->orderBy(['updated_at' => SORT_DESC,]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $imgThumb = $this->_settingImg->createImgThumb('preview-gallery', 'thumb-gallery');

        foreach ($dataProvider->getModels() as $model) {
            /** @var ArticleRepository $model */
            if ($model->image) {
                $imgThumb->web_dir = $model->getWebDir();
                $model->image = $imgThumb->checkFile($model->image);
            }
        }

        return $dataProvider;
    }

}