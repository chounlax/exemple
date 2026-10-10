---
name: front-twig
description: Conventions front du portfolio (templates Twig, CSS, AssetMapper, Stimulus, Turbo, design responsive pensé pour mettre les photos en valeur). À utiliser pour créer ou modifier un template, un composant visuel, du CSS ou du JavaScript.
---

# Front : Twig, CSS, Stimulus

## Structure des templates

- `templates/base.html.twig` : squelette (meta, en-tête, pied de page, blocs SEO).
- Une page = un template dans un dossier du nom du contrôleur
  (`templates/gallery/show.html.twig`).
- Éléments réutilisés (carte de galerie, image responsive, fil d'Ariane) dans
  `templates/components/` et appelés avec `include` ou une macro.
- Liens avec `path()`/`url()`, assets avec `asset()` / `importmap()`. Aucune URL
  écrite en dur.
- Tout texte affiché à l'utilisateur passe par `|trans` si le site doit être
  traduit ; sinon reste cohérent en français.

## CSS

- Pas de build Node : AssetMapper sert `assets/styles/`. Un fichier par grand bloc
  (`base.css`, `layout.css`, `gallery.css`…) importé depuis `app.css`.
- Variables CSS dans `:root` pour couleurs, espacements, typographie. Pas de valeur
  magique répétée.
- Mobile d'abord, grilles en CSS Grid (`grid-template-columns: repeat(auto-fill,
  minmax(...))`) ; respecte le ratio des photos avec `aspect-ratio` ou leurs
  `width`/`height`.
- Design sobre : les photos priment. Fonds neutres, beaucoup d'espace, typographie
  discrète, au plus une couleur d'accent.
- Respecte `prefers-reduced-motion` pour les animations.

## JavaScript

- Comportements dans des contrôleurs Stimulus (`assets/controllers/`), un par
  responsabilité (visionneuse, menu mobile, filtre de galerie).
- Turbo est actif : écris du JS qui se ré-initialise avec Stimulus (`connect()`), pas
  avec `DOMContentLoaded`.
- Dépendance JS externe : `php bin/console importmap:require <paquet>`.

## Vérification

Après une modification visuelle, regarde la page dans le navigateur en largeur
mobile (375 px) et bureau, puis lance la skill `qualite` (dont `lint:twig`).
