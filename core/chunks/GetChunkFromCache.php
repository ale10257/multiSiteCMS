<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 9:02
 */

namespace app\core\chunks;


use app\core\cache\CacheEntity;

class GetChunkFromCache
{
    /**
     * @var CacheEntity
     */
    private $_cache;
    /**
     * @var ChunkRepository
     */
    private $_repository;

    /**
     * GetChunkFromCache constructor.
     * @param CacheEntity $cache
     * @param ChunkRepository $repository
     */
    public function __construct(CacheEntity $cache, ChunkRepository $repository)
    {
        $this->_cache = $cache;
        $this->_repository = $repository;
    }

    /**
     * @param $alias
     * @return bool|mixed
     */
    public function get($alias)
    {
        if (!$item = $this->_cache->getItem($alias)) {
            if (!$value = $this->_repository::findOne(['alias' => $alias])) {
                return false;
            }
            $this->_cache->setItem($alias, $value->text);
        }
        return $this->_cache->getItem($alias);
    }
}