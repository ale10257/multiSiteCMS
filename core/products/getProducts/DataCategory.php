<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.01.18
 * Time: 14:32
 */

namespace app\core\products\getProducts;

use app\core\products\repositories\ProductRepository;

class DataCategory
{
    /** @var string */
    public $metaTitle;
    /** @var string */
    public $metaDescription;
    /** @var string */
    public $title;
    /** @var ProductRepository[] */
    public $products;
}