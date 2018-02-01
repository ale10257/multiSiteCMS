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
    /** @var CacheEntity */
    private $cache;
    /** @var ChunkRepository */
    private $repository;

    /**
     * GetChunkFromCache constructor.
     * @param CacheEntity $cache
     * @param ChunkRepository $repository
     */
    public function __construct(CacheEntity $cache, ChunkRepository $repository)
    {
        $this->cache = $cache;
        $this->repository = $repository;
    }

    /**
     * @param $alias
     * @return bool|mixed
     */
    public function get($alias)
    {
        if (!$item = $this->cache->getItem($alias)) {
            if (!$value = $this->repository::findOne(['alias' => $alias])) {
                return false;
            }
            $this->cache->setItem($alias, $value->text);
        }
        return $this->cache->getItem($alias);
    }
}