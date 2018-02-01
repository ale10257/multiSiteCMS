<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 7:55
 */

namespace app\core\feedback;

use yii\mail\MailerInterface;
use yii\web\UploadedFile;

class FeedbackService
{
    /** @var FeedBackForm */
    private $form;
    /** @var MailerInterface */
    private $mailer;

    /**
     * FeedbackService constructor.
     * @param FeedBackForm $form
     * @param MailerInterface $mailer
     */
    public function __construct(FeedBackForm $form, MailerInterface $mailer)
    {
        $this->form = $form;
        $this->mailer = $mailer;
    }

    /**
     * @return FeedBackForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param FeedBackForm $form
     * @param string $adminEmail
     * @param $appName
     */
    public function sendFeedback(FeedBackForm $form, string $adminEmail, $appName)
    {
        $data = new FeedbackData($form);
        $subject = 'Сообщение с сайта ' . $appName;
        $message = $this->mailer->compose('feedback_reply', ['data' => $data])
            ->setTo($adminEmail)
            ->setReplyTo($form->email)
            ->setSubject($subject)
            ->setFrom($adminEmail);
        if ($files = UploadedFile::getInstances($form, 'file')) {
            foreach ($files as $item) {
                $new_name = str_replace(basename($item->tempName), $item->name, $item->tempName);
                rename($item->tempName, $new_name);
                $message->attach($new_name);
            }
        }
        if (!$message->send()) {
            throw new \DomainException('Неизвестная ошибка при отправлении письма. Попробуйте позже.');
        }
    }
}