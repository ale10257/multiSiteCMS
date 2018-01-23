<?php

namespace app\sites\startSite;

use app\core\categories\CacheCategory;
use yii\web\Controller;


class BaseController extends Controller
{
    /**
     * @var CacheCategory
     */
    protected $cacheCategory;

    /**
     * BaseController constructor.
     * @param string $id
     * @param $module
     * @param CacheCategory $cacheCategory
     */
    public function __construct(string $id, $module, CacheCategory $cacheCategory)
    {
        $this->cacheCategory = $cacheCategory;
        parent::__construct($id, $module);

    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->viewPath = '@app/sites/startSite/views/' . $this->id;
        $this->layout = '@app/sites/startSite/views/layouts/main';
        $this->view->params['products'] = $this->cacheCategory->getTreeCategoryActive('product');
        parent::init();
    }
}
