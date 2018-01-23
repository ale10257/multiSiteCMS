<?php
namespace app\components\helpers\phpjquery;

use yii\helpers\Html;
use yii;
use yii\helpers\Url;
use app\core\workWithFiles\ImgThumb;

class PhpJqueryHelper
{
    /**
     * @param string $text
     * @param $webDir
     * @return string
     * @throws \ImagickException
     * @throws yii\base\Exception
     */
    public static function changeImages($text, $webDir)
    {
        $dom = \phpQuery::newDocumentHTML($text);

        if ($images = $dom->find('img')) {

            $imgThumb = new ImgThumb();
            $imgThumb->web_dir = $webDir;
            $imgThumb->thumb_dir = 'thumb';

            foreach ($images as $image) {
                pq($image)->parents('figure')->removeAttr('rel');
                if (pq($image)->attr('width') && pq($image)->attr('height')) {
                    $imgThumb->width = pq($image)->attr('width');
                    $imgThumb->height = pq($image)->attr('height');
                    $file = basename(pq($image)->attr('src'));
                    if ($img = $imgThumb->checkFile($file)) {
                        if (!pq($image)->parent('a')->attr('href')) {
                            $a = Html::a('', pq($image)->attr('src'), ['data-fancybox' => 'gallery']);
                            pq($image)->wrap($a);

                        }
                        pq($image)->attr('src', $img->webThumbPath);
                        pq($image)->removeAttr('style');
                        pq($image)->removeAttr('width');
                        pq($image)->removeAttr('height');

                    }
                }
            }
        }

        return pq($dom)->html();
    }

    public static function deliveryTextPrepare($text)
    {
        $dom = \phpQuery::newDocumentHTML($text);

        if ($images = $dom->find('img')) {
            foreach ($images as $image) {
                $src = Url::to(pq($image)->attr('src'), true);
                pq($image)->attr('src', $src);
            }
        }

        if ($links = $dom->find('a')) {
            foreach ($links as $link) {
                $href = Url::to(pq($link)->attr('href'), true);
                pq($link)->attr('href', $href);
            }
        }

        return pq($dom)->html();
    }


    /*public static function createGallery($text) {
        $dom = \phpQuery::newDocumentHTML($text);
        if ($galleries = pq('.insert-gallery')) {
            $setting_model = new SettingsGet();
            $settings = $setting_model->getOneSettings('preview-gallery');
            $width = $settings['width'];
            $height = $settings['height'];
            foreach ($galleries as $item) {
                if ($gallery_id = pq($item)->attr('data-id')) {
                    if ($gallery = GalleryHelper::createGallery($gallery_id, $width, $height)) {
                        pq($item)->before('<ul class="gallery">' . implode("\n", $gallery) . '</ul>');
                    }
                }
                pq($item)->remove();
            }
        }

        return pq($dom)->html();
    }*/
}
