<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 6:57
 */

namespace app\core\feedback;

use app\core\settings\GetOneSetting;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use yii\base\Model;

class FeedBackForm extends Model
{
    private $_secret_key = 'key';
    public $site_key;

    public $title;
    public $email;
    public $text;
    public $reCaptcha;
    public $name;
    public $phone;
    public $file;
    public $presentation;

    /**
     * @var GetOneSetting
     */
    private $_setting;

    /**
     * FeedBackForm constructor.
     * @param GetOneSetting $setting
     */
    public function __construct(GetOneSetting $setting)
    {
        $this->_setting = $setting;
        parent::__construct();

    }

    public function init()
    {
        if ($googleCaptcha = $this->_setting->get('google_captcha')) {
            $this->site_key = $googleCaptcha['site_key'];
            $this->_secret_key = $googleCaptcha['secret_key'];
        }
    }

    public function rules()
    {
        return [
            [['name', 'email', 'text', 'phone'], 'required'],
            [['name', 'email', 'phone', 'title', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['email', 'email'],
            ['presentation', 'boolean'],
            [
                ['file'],
                'file',
                'maxFiles' => 9,
                'extensions' => ['jpg', 'jpeg', 'png', 'tiff', 'pdf'],
                'maxSize' => 1024 * 1024 * 2,
                'tooBig' => 'Размер файла не может превышать 2-ух мегабайт'
            ],
            [['reCaptcha'], ReCaptchaValidator::className(), 'when' => function () {
                return $this->site_key;
            }, 'secret' => $this->_secret_key,]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'title' => 'Тема сообщения',
            'text' => 'Комментарий',
            'phone' => 'Телефон',
            'reCaptcha' => '',
            'presentation' => 'Заказать презентацию',
            'file' => 'Загрузить файлы (ваши образцы в форматах jpg, png, tiff, pdf)'
        ];
    }
}