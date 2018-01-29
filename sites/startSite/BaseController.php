<?php

namespace app\sites\startSite;

use app\core\cart\OrderCheckService;
use app\core\categories\CacheCategory;
use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * @var CacheCategory
     */
    protected $cacheCategory;
    /**
     * @var OrderCheckService
     */
    protected $orderCheckService;

    /**
     * BaseController constructor.
     * @param string $id
     * @param $module
     * @param CacheCategory $cacheCategory
     * @param OrderCheckService $orderCheckService
     */
    public function __construct(string $id, $module, CacheCategory $cacheCategory, OrderCheckService $orderCheckService)
    {
        $this->cacheCategory = $cacheCategory;
        $this->orderCheckService = $orderCheckService;
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
        $this->orderCheckService->checkOrderRegUser();
        $this->orderCheckService->checkTimeout();
        $this->orderCheckService->checkEmptyOrder();
        parent::init();
    }
}
