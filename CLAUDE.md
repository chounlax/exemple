# CLAUDE.md

@AGENTS.md

## Ce projet

Portfolio professionnel de photographe, en Symfony 7.4 LTS (PHP 8.4+), rendu côté
serveur avec Twig, AssetMapper, Stimulus et Turbo (pas de build Node). Doctrine ORM,
Form, Validator, Mailer et SecurityBundle sont installés via le pack `webapp`.

Objectifs, dans l'ordre : mettre les photos en valeur, chargement rapide, bon
référencement, code simple à maintenir.

Réponds à l'utilisateur en français.

## Skills du projet (`.claude/skills/`)

- `qualite` : contrôles à lancer avant de déclarer une tâche terminée.
- `galerie-photo` : upload, stockage, miniatures, affichage des images.
- `seo-portfolio` : meta, Open Graph, JSON-LD, sitemap, performance, accessibilité.
- `formulaire-contact` : contact / demande de devis, anti-spam, RGPD.
- `front-twig` : templates, CSS, Stimulus.
- `mise-en-production` : checklist de déploiement (lancée à la demande, `/mise-en-production`).

## Outils qualité installés

PHPStan niveau 6 + extension Symfony (`phpstan.dist.neon`), PHP-CS-Fixer règles
`@Symfony` (`.php-cs-fixer.dist.php`), PHPUnit.

## Où modifier quoi

- Infos du photographe (nom, ville, email, téléphone) : `globals.site` dans `config/packages/twig.yaml`.
- Galeries et photos : `src/Portfolio/GalleryCatalog.php` (pas encore de base de données).
  Les photos sont des cadres de couleur (`templates/components/_photo.html.twig`) en
  attendant les vraies images.
- Adresses du formulaire de contact : `CONTACT_FROM` / `CONTACT_TO` dans `.env.local`.
- Serveur local : `symfony serve -d` (port 8003 sur cette machine, les ports 8000-8002
  sont pris par d'autres projets).
