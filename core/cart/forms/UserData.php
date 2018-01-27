<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.01.18
 * Time: 10:18
 */

namespace app\core\cart\forms;


class UserData
{
    /** @var string */
    public $firstName;
    /** @var string */
    public $lastName;
    /** @var string */
    public $email;
    /** @var string */
    public $phone;
    /** @var string */
    public $comment;
    /** @var string */
    public $address;
    /**
     * @var OrderForm
     */
    private $_form;

    public function __construct(OrderForm $form)
    {
        $this->_form = $form;
        $this->create();
    }

    private function create()
    {
        $this->firstName = $this->_form->first_name;
        $this->lastName = $this->_form->last_name;
        $this->email = $this->_form->email;
        $this->phone = $this->_form->phone;
        $this->address = $this->_form->address;
        $this->comment = $this->_form->comment;
    }

}