<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 12:42
 */

namespace app\core\categories;


class DataCategories
{
    /** @var string */
    public $metaTitle;
    /** @var string */
    public $metaDescription;
    /** @var mixed */
    public $data;
    /** @var string */
    public $name;
    /** @var CategoryRepository[] */
    public $parents = [];

    /**
     * DataCategories constructor.
     * @param string $metaTitle
     * @param string $metaDescription
     * @param mixed $data
     * @param array $parents
     * @param string $name
     */
    public function __construct($metaTitle, $metaDescription, $data, $parents, $name = null)
    {
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
        $this->data = $data;
        $this->name = $name;
        $this->parents = $parents;
    }
}