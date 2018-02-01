<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 16:40
 */

namespace app\core\settings;

use app\core\workWithFiles\ImgThumb;

class ThumbSettingImg
{
    /** @var GetOneSetting */
    private $setting;

    /**
     * MainService constructor.
     * @param GetOneSetting $setting
     */
    public function __construct(GetOneSetting $setting)
    {
        $this->setting = $setting;
    }

    /**
     * @param string $settingName
     * @param string $thumb
     * @return ImgThumb
     */
    public function createImgThumb(string $settingName, string $thumb)
    {
        $settings = $this->setting->get($settingName);
        $imgThumb = new ImgThumb();
        if (!empty($settings['width']) && !empty($settings['height'])) {
            $imgThumb->width = $settings['width'];
            $imgThumb->height = $settings['height'];
        }
        $imgThumb->thumb_dir = $thumb;
        return $imgThumb;
    }
}