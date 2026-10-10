<?php

namespace App\Controller;

use App\Portfolio\GalleryCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/galeries')]
final class GalleryController extends AbstractController
{
    public function __construct(
        private readonly GalleryCatalog $catalog,
    ) {
    }

    #[Route('', name: 'app_gallery_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('gallery/index.html.twig', [
            'galleries' => $this->catalog->all(),
        ]);
    }

    #[Route('/{slug}', name: 'app_gallery_show', requirements: ['slug' => '[a-z0-9-]+'], methods: ['GET'])]
    public function show(string $slug): Response
    {
        $gallery = $this->catalog->find($slug) ?? throw $this->createNotFoundException('Galerie inconnue.');

        return $this->render('gallery/show.html.twig', [
            'gallery' => $gallery,
            'others' => array_values(array_filter($this->catalog->all(), static fn ($other) => $other !== $gallery)),
        ]);
    }
}
