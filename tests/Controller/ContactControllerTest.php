<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mime\Email;

final class ContactControllerTest extends WebTestCase
{
    public function testValidRequestSendsEmailAndRedirects(): void
    {
        $client = static::createClient();
        $this->submit($client, [
            'contact[name]' => 'Alex Martin',
            'contact[email]' => 'alex@example.org',
            'contact[service]' => 'mariage',
            'contact[message]' => 'Nous nous marions en juin près de Lyon et cherchons un photographe.',
        ]);

        self::assertResponseStatusCodeSame(303);
        self::assertEmailCount(1);

        $email = self::getMailerMessage();
        self::assertInstanceOf(Email::class, $email);
        self::assertEmailHeaderSame($email, 'Reply-To', 'Alex Martin <alex@example.org>');
        self::assertEmailTextBodyContains($email, 'Nous nous marions en juin');

        $client->followRedirect();
        self::assertSelectorTextContains('.alert--success', 'Merci');
    }

    public function testInvalidRequestShowsErrorsAndSendsNothing(): void
    {
        $client = static::createClient();
        $this->submit($client, [
            'contact[name]' => '',
            'contact[email]' => 'pas-un-email',
            'contact[message]' => 'Trop court',
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertEmailCount(0);
        self::assertSelectorTextContains('.field', 'Indiquez votre nom.');
        self::assertAnySelectorTextContains('.field', 'Cette adresse email n\'est pas valide.');
    }

    public function testHoneypotSilentlyDropsTheMessage(): void
    {
        $client = static::createClient();
        $this->submit($client, [
            'contact[name]' => 'Robot',
            'contact[email]' => 'robot@example.org',
            'contact[service]' => 'autre',
            'contact[message]' => 'Message automatique envoyé par un robot de spam.',
            'contact[website]' => 'https://spam.example',
        ]);

        self::assertResponseStatusCodeSame(303);
        self::assertEmailCount(0);
    }

    /**
     * @param array<string, string> $values
     */
    private function submit(KernelBrowser $client, array $values): void
    {
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('Envoyer ma demande')->form($values);
        $client->submit($form);
    }
}
