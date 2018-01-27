<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 08.01.18
 * Time: 12:43
 */

namespace app\core\articles\getArticles;


class DataArticle
{
    /** @var bool|\app\core\categories\CategoryRepository */
    public $category;
    /** @var string */
    public $metaTitle;
    /** @var string */
    public $metaDescription;
    /** @var string */
    public $title;
    /** @var string */
    public $articleShortText;
    /** @var string */
    public $articleText;
    /** @var string */
    public $articleImage;
    /** @var \app\core\workWithFiles\DataPathImage [] */
    public $articleGallery = [];

}