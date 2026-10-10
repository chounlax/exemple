<?php

namespace App\Controller;

use App\Portfolio\GalleryCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(GalleryCatalog $catalog): Response
    {
        return $this->render('home/index.html.twig', [
            'galleries' => $catalog->all(),
        ]);
    }
}
