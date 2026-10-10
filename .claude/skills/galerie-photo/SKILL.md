---
name: galerie-photo
description: Conventions pour les photos du portfolio (upload, stockage, miniatures WebP/AVIF, srcset, lazy loading, EXIF, textes alternatifs, galeries et séries). À utiliser pour toute tâche qui touche aux images, aux galeries, aux albums, aux projets photo ou à leur affichage.
---

# Photos et galeries

Le site est un portfolio de photographe professionnel : les images sont le produit.
Elles doivent être nettes, rapides à charger, et ne jamais déformer la mise en page.

## Avant de coder, demande si ce n'est pas précisé

- D'où viennent les photos : interface d'administration (upload), ou fichiers
  déposés dans un dossier et importés par une commande ?
- Organisation : séries/projets, catégories (mariage, portrait, corporate…), tags ?
- Faut-il un espace client privé (galerie protégée par mot de passe ou lien) ?
- Filigrane (watermark) sur les images publiques : oui ou non ?

## Modèle de données (Doctrine)

- `Photo` : fichier original, `alt` (obligatoire), titre, légende, largeur, hauteur,
  date de prise de vue, position (ordre manuel), `isPublished`.
- `Gallery` (série/projet) : titre, `slug` unique, description, photo de couverture,
  position, `isPublished`, et une relation ordonnée vers `Photo`.
- Slugs générés avec `Symfony\Component\String\Slugger\SluggerInterface`.
- Toute modification de schéma passe par `make:migration` puis
  `doctrine:migrations:migrate`.

## Upload et stockage

- Formulaire Symfony avec `FileType` et une contrainte `#[Assert\Image]` :
  `mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/avif']`,
  `maxSize` explicite, et `minWidth` pour refuser les photos trop petites.
- Nom de fichier aléatoire (pas le nom d'origine), généré côté serveur.
- Originaux stockés hors de `public/` (par exemple `var/storage/photos/` ou un
  stockage objet). Seules les versions redimensionnées sont servies.
- Le code d'upload vit dans un service dédié, pas dans le contrôleur.
- Enregistre largeur et hauteur à l'upload (`getimagesize()`) pour les attributs
  `width`/`height` du HTML.

## Versions redimensionnées

- Utilise `liip/imagine-bundle` (`composer require liip/imagine-bundle`) plutôt
  qu'un redimensionnement écrit à la main. Définis des filtres nommés par usage
  (`thumb_400`, `gallery_800`, `gallery_1600`, `full_2400`, `og_1200x630`).
- Sortie en WebP (et AVIF si la librairie du serveur le permet), qualité 80-85.
- Les versions publiques ne gardent pas les métadonnées EXIF (surtout la position
  GPS). Le copyright peut être affiché dans la légende ou le JSON-LD.

## Affichage (Twig)

- Toujours `width`, `height` et `alt` sur `<img>` : pas de décalage de mise en page.
- `srcset` + `sizes` avec les filtres ci-dessus. Mets ce balisage dans un composant
  Twig réutilisable (macro ou `include`), jamais recopié page par page.
- `loading="lazy"` et `decoding="async"` sur toutes les images sauf la première
  visible ; celle-ci reçoit `fetchpriority="high"` et pas de lazy loading.
- Le texte `alt` décrit la photo (sujet, lieu, ambiance) : il sert à l'accessibilité
  et au référencement d'images. Pas de « photo 1 » ni de nom de fichier.
- Visionneuse plein écran : un contrôleur Stimulus, utilisable au clavier
  (flèches, Échap) et avec focus visible.

## Tests

- Test fonctionnel d'upload avec `UploadedFile` sur une image de
  `tests/fixtures/`, vérifiant l'enregistrement et le refus d'un fichier invalide.
- Test qu'une galerie non publiée renvoie 404.
