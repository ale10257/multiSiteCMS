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
    private $_cache;

    /**
     * GetSetting constructor.
     * @param CacheEntity $cache
     */
    public function __construct(CacheEntity $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @param string $settingName
     * @return array
     */
    public function get(string $settingName)
    {
        if (!$tree = $this->_cache->getItem($this->_cache::SETTING_TREE)) {
            $settings = new SettingRepository();
            $this->_cache->setItem($this->_cache::SETTING_TREE, $settings->getTree());
            $tree = $this->_cache->getItem($this->_cache::SETTING_TREE);
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