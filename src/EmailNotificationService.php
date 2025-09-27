<?php

namespace Jakubcesar\EmailNotifier;

use Nette\Mail\Mailer;
use Nette\Mail\Message;

final class EmailNotificationService
{
    public function __construct
    (
        private Mailer $mailer
    ){}

    public function send(string $from, string $to, string $subject, string $body): void
    {
        $msg = new Message();
        $msg->setFrom($from);
        $msg->addTo($to);
        $msg->setSubject($subject);
        $msg->setBody($body);

        $this->mailer->send($msg);
    }
}