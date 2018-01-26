<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.01.18
 * Time: 14:05
 */

namespace app\sites\startSite\controllers;

use app\core\cart\OrderCheckService;
use app\core\cart\OrderProductService;
use app\core\categories\CacheCategory;
use app\core\products\getProducts\GetProduct;
use app\sites\startSite\BaseController;
use Yii;
use yii\filters\VerbFilter;

class ProductController extends BaseController
{
    /** @var GetProduct */
    private $_product;
    /** @var OrderProductService */
    private $_productService;


    /**
     * ProductController constructor.
     * @param $id
     * @param $module
     * @param CacheCategory $cacheCategory
     * @param OrderCheckService $orderCheckService
     * @param GetProduct $product
     * @param OrderProductService $productService
     */
    public function __construct(
        $id,
        $module,
        CacheCategory $cacheCategory,
        OrderCheckService $orderCheckService,
        GetProduct $product,
        OrderProductService $productService)
    {
        $this->_product = $product;
        $this->_productService = $productService;
        parent::__construct($id, $module, $cacheCategory, $orderCheckService);
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $arr = parent::behaviors();
        $arr['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'add' => ['post'],
            ],
        ];

        return $arr;
    }

    /**
     * @param $alias
     * @return string
     * @throws \ImagickException
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCategory($alias)
    {
        $dataCategory = $this->_product->getCategory($alias);
        return $this->render('category', ['dataCategory' => $dataCategory]);
    }

    /**
     * @param $id_alias
     * @return string
     * @throws \ImagickException
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id_alias)
    {
        $product = $this->_product->getProduct($id_alias);

        return $this->render('view', [
            'product' => $product,
            'formProduct' => $this->_productService->getNewForm(),
            'checkProduct' => $this->_productService->checkOrderedProduct($product->id)
        ]);
    }


    /**
     * @return bool|string|\yii\web\Response
     */
    public function actionAdd()
    {
        $formProduct = $this->_productService->getNewForm();

        if ($formProduct->load(yii::$app->request->post()) && $formProduct->validate()) {
            try {
                $this->_productService->create($formProduct);
                return $this->renderPartial('_ordered_form');
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return false;
    }

}