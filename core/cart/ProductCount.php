<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.18
 * Time: 12:25
 */

namespace app\core\cart;


class ProductCount
{
    /** @var int  */
    public $all_num = 0;

    /** @var int  */
    public $sum = 0;

    /** @var int  */
    public $percent = 0;

    /** @var int  */
    public $discount = 0;

    /** @var int  */
    public $total = 0;

    /** @var array  */
    public $product_ids = [];
}