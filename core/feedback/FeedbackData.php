<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.01.18
 * Time: 16:21
 */

namespace app\core\feedback;


class FeedbackData
{
    /** @var string */
    public $name;
    /** @var string */
    public $email;
    /** @var string */
    public $text;
    /** @var string */
    public $phone;

    /** @var FeedBackForm */
    private $_form;

    /**
     * FeedbackData constructor.
     * @param FeedBackForm $form
     */
    public function __construct(FeedBackForm $form)
    {
        $this->_form = $form;
        $this->create();
    }

    private function create()
    {
        $this->name = $this->_form->name;
        $this->email = $this->_form->email;
        $this->text = $this->_form->text;
        $this->phone = $this->_form->phone;
    }
}