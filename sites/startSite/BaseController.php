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
    protected $_cacheCategory;
    /**
     * @var OrderCheckService
     */
    private $_orderCheckService;

    /**
     * BaseController constructor.
     * @param string $id
     * @param $module
     * @param CacheCategory $cacheCategory
     * @param OrderCheckService $orderCheckService
     */
    public function __construct(string $id, $module, CacheCategory $cacheCategory, OrderCheckService $orderCheckService)
    {
        $this->_cacheCategory = $cacheCategory;
        $this->_orderCheckService = $orderCheckService;
        parent::__construct($id, $module);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->viewPath = '@app/sites/startSite/views/' . $this->id;
        $this->layout = '@app/sites/startSite/views/layouts/main';
        $this->view->params['products'] = $this->_cacheCategory->getTreeCategoryActive('product');
        $this->_orderCheckService->checkOrderRegUser();
        $this->_orderCheckService->checkTimeout();
        $this->_orderCheckService->checkEmptyOrder();
        parent::init();
    }
}
