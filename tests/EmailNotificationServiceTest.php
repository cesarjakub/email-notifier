<?php

declare(strict_types=1);

use Jakubcesar\EmailNotifier\EmailNotificationService;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class DummyMailer implements Mailer
{
    public ?Message $sent = null;

    public function send(Message $mail): void
    {
        $this->sent = $mail;
    }

    public function getLast(): ?Message
    {
        return $this->sent;
    }
}

final class EmailNotificationServiceTest extends TestCase
{
    private DummyMailer $mailer;
    private EmailNotificationService $service;

    protected function setUp(): void
    {
        $this->mailer = new DummyMailer();
        $this->service = new EmailNotificationService($this->mailer);
    }

    public function testSendEmail(): void
    {
        $this->service->send(
            'from@example.com',
            'to@example.com',
            'Hello subject',
            '<p>Hello body</p>'
        );

        $sent = $this->mailer->getLast();
        Assert::type(Message::class, $sent);
        Assert::same('Hello subject', $sent->getSubject());
        Assert::same(['from@example.com' => null], $sent->getFrom());
        Assert::same(['to@example.com' => null], $sent->getHeader('To'));
        Assert::contains('<p>Hello body</p>', $sent->getHtmlBody());
    }
}
(new EmailNotificationServiceTest())->run();
