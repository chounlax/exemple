<?php

namespace App\Controller;

use App\Portfolio\GalleryCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SeoController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', format: 'xml', methods: ['GET'])]
    public function sitemap(GalleryCatalog $catalog): Response
    {
        $response = $this->render('seo/sitemap.xml.twig', [
            'galleries' => $catalog->all(),
        ]);
        $response->headers->set('Content-Type', 'application/xml; charset=UTF-8');

        return $response;
    }

    #[Route('/robots.txt', name: 'app_robots', format: 'txt', methods: ['GET'])]
    public function robots(#[Autowire('%kernel.environment%')] string $environment): Response
    {
        $response = $this->render('seo/robots.txt.twig', [
            'allow_indexing' => 'prod' === $environment,
        ]);
        $response->headers->set('Content-Type', 'text/plain; charset=UTF-8');

        return $response;
    }
}
