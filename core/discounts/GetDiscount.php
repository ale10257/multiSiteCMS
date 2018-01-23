<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 19:57
 */

namespace app\core\discounts;


use app\core\cache\CacheEntity;

class GetDiscount
{
    /**
     * @var CacheEntity
     */
    private $_cache;

    private $_discounts;

    public function __construct(CacheEntity $cache)
    {
        $this->_cache = $cache;
        $this->checkCache();
    }

    public function getDiscountPercent(int $sum)
    {
        $percent = 0;

        if ($this->_discounts) {
            foreach ($this->_discounts as $discount) {
                if ($sum >= $discount->start_sum) {
                    $percent = $discount->percent;
                }
            }
        }

        return $percent;
    }

    public function getFirstDiscount()
    {
        $arr = [
            'sum' => null,
            'percent' => null
        ];

        if ($this->_discounts) {
            $i = 0;
            foreach ($this->_discounts as $item) {
                if ($i < 1) {
                    /** @var DiscountRepository $item */
                    $arr['percent'] = $item->percent;
                    $arr['start_sum'] = $item->start_sum;
                }
                $i++;
            }
        }

        return $arr;
    }

    private function checkCache () {
        if (!$this->_discounts = $this->_cache->getItem($this->_cache::DISCOUNT)) {
            $discounts = DiscountRepository::find()->orderBy(['start_sum' => SORT_ASC])->all();
            $this->_cache->setItem($this->_cache::DISCOUNT, $discounts);
        }
        $this->_discounts = $this->_cache->getItem($this->_cache::DISCOUNT);
    }
}