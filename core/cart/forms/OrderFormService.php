<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 06.01.18
 * Time: 15:09
 */

namespace app\core\cart\forms;

use app\components\user\User;
use app\core\userReg\UserRegRepository;

class OrderFormService
{
    /**
     * @var User
     */
    private $_user;

    public function __construct(User $user)
    {
        $this->_user = $user;
    }

    /**
     * @return OrderForm
     */
    public function getForm()
    {
        $form = new OrderForm();
        if (!$this->_user->isGuest) {
            $form->first_name = $this->_user->identity->getFirstName();
            $form->last_name = $this->_user->identity->getLastName();
            $form->email = $this->_user->identity->getEmail();
            if ($this->_user->identity->getRole() == 'reg_user') {
                $reg = UserRegRepository::findOne(['users_id' => $this->_user->id]);
                $form->phone = $reg->phone;
                $form->address = $reg->address;
            }
        }

        return $form;
    }
}