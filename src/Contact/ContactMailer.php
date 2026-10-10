<?php

namespace App\Contact;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class ContactMailer
{
    public function __construct(
        private readonly MailerInterface $mailer,
        #[Autowire('%env(CONTACT_FROM)%')]
        private readonly string $from,
        #[Autowire('%env(CONTACT_TO)%')]
        private readonly string $to,
    ) {
    }

    public function send(ContactRequest $request): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->from, 'Site portfolio'))
            ->to($this->to)
            ->replyTo(new Address((string) $request->email, (string) $request->name))
            ->subject(\sprintf('Demande de contact : %s – %s', $request->serviceLabel(), $request->name))
            ->textTemplate('email/contact.txt.twig')
            ->context(['request' => $request]);

        $this->mailer->send($email);
    }
}
