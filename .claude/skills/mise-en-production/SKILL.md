---
name: mise-en-production
description: Liste de contrôle pour mettre en ligne ou déployer le portfolio Symfony (environnement prod, secrets, assets, HTTPS, en-têtes de sécurité, cache, mentions légales). À utiliser quand l'utilisateur parle de déploiement, d'hébergement, de mise en ligne ou de production.
disable-model-invocation: true
---

# Mise en production

Ne déploie rien et ne te connecte à aucun serveur sans l'accord explicite de
l'utilisateur. Cette skill sert à préparer et vérifier.

## Configuration

- `APP_ENV=prod`, `APP_DEBUG=0`, un `APP_SECRET` propre au serveur.
- Secrets (mot de passe BDD, `MAILER_DSN`) dans le coffre :
  `php bin/console secrets:set NOM --env=prod`, ou variables d'environnement du
  serveur. Jamais dans `.env` commité.
- `composer install --no-dev --optimize-autoloader --classmap-authoritative`
- `php bin/console asset-map:compile`
- `php bin/console doctrine:migrations:migrate --no-interaction`
- `php bin/console cache:clear` puis `cache:warmup`
- OPcache activé ; `composer dump-env prod` pour éviter de relire `.env`.

## Serveur

- HTTPS obligatoire, redirection HTTP vers HTTPS, HSTS.
- En-têtes : `Content-Security-Policy`, `X-Content-Type-Options: nosniff`,
  `Referrer-Policy`, `Permissions-Policy`.
- Cache long sur `public/assets/` et sur les images redimensionnées.
- Le dossier des originaux n'est pas accessible depuis le web.
- Sauvegarde régulière de la base et des photos originales.

## Légal (site professionnel en France)

- Page « Mentions légales » : identité (nom, statut, SIRET), contact, hébergeur.
- Page « Confidentialité » : données collectées par le formulaire, durée, droits.
- Pas de bandeau cookies si aucun traceur ; préfère une mesure d'audience sans
  cookie. Sinon, bandeau de consentement avec refus aussi simple que l'accord.
- Mention du droit d'auteur sur les photos.

## Vérifications finales

- Lance la skill `qualite`.
- Teste en prod : pages 404/500 personnalisées, pas de barre de debug, formulaire de
  contact qui envoie vraiment, `sitemap.xml` et `robots.txt` corrects.
- Mesure PageSpeed / Lighthouse sur l'accueil et une galerie.
