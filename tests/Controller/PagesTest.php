<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PagesTest extends WebTestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function publicPages(): iterable
    {
        yield 'accueil' => ['/', 'Des images vraies, pour les moments qui comptent.'];
        yield 'galeries' => ['/galeries', 'Galeries'];
        yield 'galerie' => ['/galeries/mariages', 'Mariages'];
        yield 'à propos' => ['/a-propos', 'Bonjour, je suis'];
        yield 'contact' => ['/contact', 'Parlons de votre projet'];
        yield 'mentions légales' => ['/mentions-legales', 'Mentions légales'];
    }

    #[DataProvider('publicPages')]
    public function testPublicPageIsReachableWithSeoTags(string $url, string $heading): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', $heading);
        self::assertCount(1, $crawler->filter('h1'));
        self::assertSelectorExists('link[rel="canonical"]');
        self::assertNotEmpty($crawler->filter('meta[name="description"]')->attr('content'));
    }

    public function testHomeListsEveryGallery(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertCount(4, $crawler->filter('.card'));
        $jsonLd = json_decode($crawler->filter('script[type="application/ld+json"]')->text(), true, flags: \JSON_THROW_ON_ERROR);
        self::assertSame('ProfessionalService', $jsonLd['@type']);
    }

    public function testGalleryShowsNumberedPhotoFrames(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/galeries/portraits');

        $photos = $crawler->filter('.masonry .photo');
        self::assertCount(9, $photos);
        self::assertSame('Photo 1', $photos->first()->text());
        self::assertSame('Photo 9', $photos->last()->text());
        self::assertSame('Portraits – photo 1', $photos->first()->attr('aria-label'));
        self::assertSelectorExists('dialog.lightbox');
    }

    public function testUnknownGalleryReturns404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/galeries/inconnue');

        self::assertResponseStatusCodeSame(404);
    }

    public function testSitemapListsGalleries(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/xml; charset=UTF-8');
        $content = (string) $client->getResponse()->getContent();
        self::assertStringContainsString('/galeries/mariages</loc>', $content);
        self::assertNotFalse(simplexml_load_string($content));
    }

    public function testRobotsBlocksIndexingOutsideProduction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/robots.txt');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Disallow: /', (string) $client->getResponse()->getContent());
    }
}
