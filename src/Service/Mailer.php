<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($data)
    {
        $email = (new Email())
            ->from($_ENV['MAILER_EMAIL'])
            ->to($_ENV['MAILER_EMAIL'])
            ->subject($data['subject'])
            ->html($data['html']);

        $this->mailer->send($email);
    }
}