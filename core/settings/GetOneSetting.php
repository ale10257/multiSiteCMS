<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 7:30
 */

namespace app\core\settings;

use app\core\cache\CacheEntity;

class GetOneSetting
{
    /** @var CacheEntity  */
    private $cache;
    /**@var SettingRepository */
    private $repository;

    /**
     * GetSetting constructor.
     * @param CacheEntity $cache
     * @param SettingRepository $repository
     */
    public function __construct(CacheEntity $cache, SettingRepository $repository)
    {
        $this->cache = $cache;
        $this->repository = $repository;
    }

    /**
     * @param string $settingName
     * @return array
     */
    public function get(string $settingName)
    {
        if (!$tree = $this->cache->getItem($this->cache::SETTING_TREE)) {
            $this->cache->setItem($this->cache::SETTING_TREE, $this->repository->getTree());
            $tree = $this->cache->getItem($this->cache::SETTING_TREE);
        }

        /** @var SettingRepository[] $tree */
        $result = [];
        $key = false;

        foreach ($tree as $item) {
            if ($item->alias == $settingName && $item->active && $item->depth == 1) {
                $key = true;
            }
            if ($key && $item->depth == 2) {
                $result[$item->alias] = $item->value;
            }
            if ($key && $item->alias !== $settingName && $item->depth == 1) {
                break;
            }
        }

        return $result;
    }
}