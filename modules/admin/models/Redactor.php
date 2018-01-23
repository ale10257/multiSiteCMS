<?php

namespace app\modules\admin\models;

use yii\base\Model;

/**
 * Class Redactor
 * @package app\modules\admin\models
 *
 */

class Redactor extends Model
{
    public $file;

    public function rules()
    {
        return [
            ['file', 'file', 'extensions' => ['jpg', 'png', 'jpeg', 'pdf', 'docx', 'doc', 'xls', 'xlsx', 'zip', 'rar'], 'maxSize' => 2 * 1024 * 1024, 'tooBig' => 'Размер файла не может превышать 2 мегабайта']
        ];
    }
}
