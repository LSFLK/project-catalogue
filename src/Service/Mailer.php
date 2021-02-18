<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class Mailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($data)
    {
        $email = (new TemplatedEmail())
            ->from($_ENV['MAILER_EMAIL'])
            ->to($_ENV['MAILER_EMAIL'])
            ->subject($data['subject'])
            ->htmlTemplate($data['htmlTemplate'])
            ->context($data['context']);

        if(isset($data['replyTo'])) {
            $email -> replyTo($data['replyTo']);
        }

        $this->mailer->send($email);
    }
}