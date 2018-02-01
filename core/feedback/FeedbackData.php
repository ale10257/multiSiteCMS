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
    private $form;

    /**
     * FeedbackData constructor.
     * @param FeedBackForm $form
     */
    public function __construct(FeedBackForm $form)
    {
        $this->form = $form;
        $this->create();
    }

    private function create()
    {
        $this->name = $this->form->name;
        $this->email = $this->form->email;
        $this->text = $this->form->text;
        $this->phone = $this->form->phone;
    }
}