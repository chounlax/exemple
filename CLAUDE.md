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
