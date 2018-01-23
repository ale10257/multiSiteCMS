<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.12.17
 * Time: 17:38
 */

namespace app\core\categories;

use app\core\cache\CacheEntity;

class CacheCategory
{
    /**
     * @var CacheEntity
     */
    private $_cache;

    /**
     * ServiceCacheCategory constructor.
     * @param CacheEntity $cache
     */
    public function __construct(CacheEntity $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @param string $type
     * @return CategoryRepository[]
     */
    public function getTreeCategory(string $type)
    {
        $key = $this->checkKey($type, 'tree');
        if (!$tree = $this->_cache->getItem($key)) {
            $typeRoot = $this->getRoot($type);
            /** @var CategoryRepository[] $children */
            $children = $typeRoot->children()->all();
            foreach ($children as $item) {
                $item->parents_array = $item->parents()->andWhere(['>', 'depth', 1])->all();
            }
            $this->_cache->setItem($key, $children);
            $tree = $this->_cache->getItem($key);
        }
        return $tree;
    }

    /**
     * @param string $type
     * @return CategoryRepository[]
     */
    public function getTreeCategoryActive(string $type)
    {
        $tree = $this->getTreeCategory($type);
        foreach ($tree as $key => $item) {
            if (!$item->active) {
                unset($tree[$key]);
            }
        }

        return $tree;
    }

    /**
     * @param string $type
     * @return CategoryRepository[]
     */
    public function getLeavesCategory(string $type)
    {
        $key = $this->checkKey($type);
        if (!$leaves = $this->_cache->getItem($key)) {
            $typeRoot = $this->getRoot($type);
            $this->_cache->setItem($key, $typeRoot->leaves()->all());
            $leaves = $this->_cache->getItem($key);
        }
        return $leaves;
    }

    /**
     * @param string $type
     * @return CategoryRepository[]
     */
    public function getLeavesCategoryActive(string $type)
    {
        $leaves = $this->getLeavesCategory($type);
        foreach ($leaves as $key => $item) {
            if (!$item->active) {
                unset($leaves[$key]);
            }
        }

        return $leaves;
    }

    private function checkKey(string $type, string $tree = null)
    {
        $key = null;
        if ($type == CategoryRepository::RESERVED_TYPE_ARTICLE) {
            $key = $tree ? $this->_cache::CATEGORY_CACHE['article_tree'] : $this->_cache::CATEGORY_CACHE['leaves_article'];
        }
        if ($type == CategoryRepository::RESERVED_TYPE_PRODUCT) {
            $key = $tree ? $this->_cache::CATEGORY_CACHE['product_tree'] : $this->_cache::CATEGORY_CACHE['leaves_product'];
        }
        if (!$key) {
            throw new \DomainException('Undefined category type for cache category.');
        }
        return $key;
    }

    /**
     * @param string $type
     * @return CategoryRepository
     */
    private function getRoot(string $type)
    {
        $root = CategoryRepository::findOne(['name' => SITE_ROOT_NAME]);
        return $root->children(1)->andWhere(['type_category' => $type])->one();
    }
}