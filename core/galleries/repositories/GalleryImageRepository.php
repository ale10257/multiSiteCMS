<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 13:16
 */

namespace app\core\galleries\repositories;

use app\core\base\BaseRepository;
use app\core\galleries\forms\GalleryImageForm;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\Sort;

/**
 * This is the model class for table "gallery_images".
 *
 * @property int $id
 * @property int $galleries_id
 * @property string $name
 * @property string $alt
 * @property string $title_link
 * @property int $sort
 *
 * @property GalleryRepository $gallery
 */
class GalleryImageRepository extends BaseRepository
{
    use Sort;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery_images';
    }

    /**
     * @param GalleryImageForm $form
     */
    public function insertValues($form)
    {
        if (!$form->sort) {
            $form->sort = $this->getNumLastElement(['galleries_id' => $form->galleries_id], 'sort');
        }

        InsertValuesHelper::insertValues($this, $form, [
            'galleries_id',
            'name',
            'alt',
            'title_link',
            'sort',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(GalleryRepository::className(), ['id' => 'galleries_id']);
    }

    public function getWebDir()
    {
        return $this->gallery->getWebDir();
    }
}