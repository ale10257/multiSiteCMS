<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.01.18
 * Time: 7:19
 */

namespace app\sites\startSite\controllers;


use app\core\articles\getArticles\ArticleGetService;
use app\core\articles\getArticles\ArticleSearchActive;
use app\core\categories\CacheCategory;
use app\sites\startSite\BaseController;

class ArticleController extends BaseController
{
    /**
     * @var ArticleGetService
     */
    private $_service;
    /**
     * @var ArticleSearchActive
     */
    private $_searchArticles;

    /**
     * ArticleController constructor.
     * @param string $id
     * @param $module
     * @param CacheCategory $cacheCategory
     * @param ArticleGetService $service
     * @param ArticleSearchActive $searchArticles
     */
    public function __construct(string $id, $module, CacheCategory $cacheCategory, ArticleGetService $service, ArticleSearchActive $searchArticles)
    {
        $this->_service = $service;
        parent::__construct($id, $module, $cacheCategory);
        $this->_searchArticles = $searchArticles;
    }

    /**
     * @param $alias
     * @return string
     * @throws \ImagickException
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionOneArticle($alias)
    {
        $article = $this->_service->getOneArticle($alias);
        return $this->render('one-article', ['article' => $article]);
    }

    /**
     * @param $alias
     * @return string
     * @throws \ImagickException
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAnyArticles($alias)
    {
        $category = $this->_searchArticles->getCategory($alias);
        $dataProvider = $this->_searchArticles->search($category->id);

        return $this->render('list-articles', ['dataProvider' => $dataProvider, 'category' => $category]);
    }
}