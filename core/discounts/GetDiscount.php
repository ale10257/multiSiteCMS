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
    /** @var CacheEntity */
    private $cache;
    /** @var DiscountRepository[] */
    private $discounts;

    /**
     * GetDiscount constructor.
     * @param CacheEntity $cache
     */
    public function __construct(CacheEntity $cache)
    {
        $this->cache = $cache;
        $this->checkCache();
    }

    /**
     * @param int $sum
     * @return int
     */
    public function getDiscountPercent(int $sum)
    {
        $percent = 0;

        if ($this->discounts) {
            foreach ($this->discounts as $discount) {
                if ($sum >= $discount->start_sum) {
                    $percent = $discount->percent;
                }
            }
        }

        return $percent;
    }

    /**
     * @return array
     */
    public function getFirstDiscount()
    {
        $arr = [
            'sum' => null,
            'percent' => null
        ];

        if ($this->discounts) {
            $i = 0;
            foreach ($this->discounts as $item) {
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

    private function checkCache () : void
    {
        if (!$this->discounts = $this->cache->getItem($this->cache::DISCOUNT)) {
            $discounts = DiscountRepository::find()->orderBy(['start_sum' => SORT_ASC])->all();
            $this->cache->setItem($this->cache::DISCOUNT, $discounts);
        }
        $this->discounts = $this->cache->getItem($this->cache::DISCOUNT);
    }
}