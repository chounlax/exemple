---
name: formulaire-contact
description: Formulaire de contact ou de demande de devis/réservation du portfolio (Symfony Form, Validator, Mailer, anti-spam, limite de débit, RGPD). À utiliser pour tout formulaire public envoyé par un visiteur.
---

# Formulaire de contact / demande de devis

## Construction

- Un DTO `readonly` (par exemple `ContactRequest`) avec des contraintes
  `#[Assert\...]` : nom, email (`#[Assert\Email]`), type de prestation
  (`#[Assert\Choice]`), date souhaitée (optionnelle), message (longueur min/max).
- Un `FormType` dédié lié à ce DTO. Protection CSRF activée (par défaut).
- Le contrôleur reste court : il valide le formulaire, appelle un service
  (`ContactMailer`, par exemple) puis redirige (PRG) avec un message flash.
- Email envoyé avec Symfony Mailer et un `TemplatedEmail` (template Twig).
  `replyTo` = email du visiteur ; `from` = adresse du domaine du site, configurée
  par variable d'environnement, jamais l'adresse du visiteur.
- Destinataire et expéditeur lus avec `#[Autowire('%env(...)%')]`, valeurs par
  défaut dans `.env`, vraies valeurs dans `.env.local` ou `secrets:set`.

## Anti-spam

- Champ piège (honeypot) caché, non rempli par les humains : si rempli, on
  répond comme si tout allait bien mais on n'envoie rien.
- `symfony/rate-limiter` (`composer require symfony/rate-limiter`) : quelques
  envois par IP et par heure.
- Pas de CAPTCHA tiers par défaut (mauvais pour la vie privée et l'accessibilité) ;
  à proposer seulement si le spam persiste.

## RGPD

- Ne stocke pas les messages en base sauf demande explicite ; si oui, prévois une
  durée de conservation et une commande de purge.
- Texte d'information sous le formulaire (finalité, destinataire, durée) avec un
  lien vers la page de confidentialité.

## Tests

`WebTestCase` : soumission valide (email envoyé, vérifié avec
`assertEmailCount(1)` et redirection), soumission invalide (erreurs affichées, aucun
email), honeypot rempli (aucun email). En test, le transport Mailer est `null://`.
