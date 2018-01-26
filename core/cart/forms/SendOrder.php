<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.01.18
 * Time: 8:34
 */

namespace app\core\cart\forms;

use app\core\cart\OrderService;
use app\core\NotFoundException;
use yii\mail\MailerInterface;
use yii\web\Session;
use app\core\cart\repositories\OrderRepository;

class SendOrder
{
    /** @var MailerInterface */
    private $_mailer;
    /** @var Session */
    private $_session;
    /** @var OrderService */
    private $_order;

    /**
     * SendOrder constructor.
     * @param MailerInterface $mailer
     * @param Session $session
     * @param OrderService $order
     */
    public function __construct(MailerInterface $mailer, Session $session, OrderService $order)
    {

        $this->_mailer = $mailer;
        $this->_session = $session;
        $this->_order = $order;
    }

    /**
     * @param OrderForm $form
     * @param string $adminEmail
     * @param string $appName
     * @return bool
     * @throws \yii\db\Exception
     */
    public function sendEmail(OrderForm $form, string $adminEmail, string $appName)
    {
        if (!$order_id = $this->_session->get($this->_order::SESSION_KEY)) {
            throw new NotFoundException('Заказ не найден');
        }

        $user_data = new UserData($form);

        $order = $this->_order->getOrderForSend($order_id);
        $this->_order->createData($order, json_encode($user_data));

        $admin_mail = $this->_mailer
            ->compose(
                ['html' => 'sendOrder'],
                ['user_data' => $user_data, 'order_products' => $order->orderProducts, 'order_id' => $order_id]
            )
            ->setFrom($adminEmail)
            ->setSubject('Заказ на сайте ' . $appName . ' #' . $order_id);

        $user_mail = clone $admin_mail;
        $admin_mail->setReplyTo($form->email)->setTo($adminEmail);
        $user_mail->setTo($form->email);

        if ($admin_mail->send() && $user_mail->send()) {
            $this->_order->changeStatus($order_id, OrderRepository::STATUS_ORDER_NOT_VERIFED);
            $this->_session->remove($this->_order::SESSION_KEY);
            return true;
        }

        throw new \DomainException('Неизвестная ошибка при отправлении письма, попробуйте позже.');
    }
}