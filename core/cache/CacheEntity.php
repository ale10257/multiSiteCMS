<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.12.17
 * Time: 8:43
 */

namespace app\core\cache;

use yii\caching\CacheInterface;

class CacheEntity
{
    /** @var CacheInterface $_cache */
    private $_cache;

    const SETTING_TREE = 'setting_tree';

    const CATEGORY_CACHE = [
        'leaves_product' => 'leaves_product',
        'leaves_article' => 'leaves_article',
        'product_tree' => 'product_tree',
        'article_tree' => 'article_tree',
    ];

    const DISCOUNT = 'discount';

    /**
     * CacheEntity constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getItem($key)
    {
        return $this->_cache->get($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setItem($key, $value)
    {
        $this->_cache->set($key, $value);
    }

    /**
     * @param $key
     */
    public function deleteItem($key)
    {
        if (!is_array($key)) {
            if ($this->_cache->exists($key)) {
                $this->_cache->delete($key);
            }
            return;
        }

        foreach ($key as $item) {
            if ($this->_cache->exists($item)) {
                $this->_cache->delete($item);
            }
        }
    }
}