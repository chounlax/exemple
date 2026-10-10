<?php

namespace App\Controller;

use App\Contact\ContactMailer;
use App\Contact\ContactRequest;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ContactMailer $mailer,
        #[Target('contact_form')]
        RateLimiterFactoryInterface $contactFormLimiter,
        #[Autowire('%env(bool:STATIC_SITE)%')]
        bool $staticSite,
    ): Response {
        // Site statique (GitHub Pages) : pas de serveur pour traiter le formulaire.
        if ($staticSite) {
            return $this->render('contact/index.html.twig', ['form' => null]);
        }

        $contact = new ContactRequest();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Un robot a rempli le champ piège : on fait comme si tout allait bien.
            if ('' !== (string) $form->get(ContactType::HONEYPOT)->getData()) {
                return $this->redirectWithSuccess();
            }

            if (!$contactFormLimiter->create($request->getClientIp())->consume()->isAccepted()) {
                $this->addFlash('error', 'Trop de messages envoyés. Réessayez dans une heure ou écrivez-moi directement par email.');

                return $this->redirectToRoute('app_contact', status: Response::HTTP_SEE_OTHER);
            }

            $mailer->send($contact);

            return $this->redirectWithSuccess();
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }

    private function redirectWithSuccess(): Response
    {
        $this->addFlash('success', 'Merci, votre message est bien envoyé. Je vous réponds sous 48 heures.');

        return $this->redirectToRoute('app_contact', status: Response::HTTP_SEE_OTHER);
    }
}
