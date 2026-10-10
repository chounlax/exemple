---
name: seo-portfolio
description: Référencement, performance et accessibilité du portfolio (balises meta, Open Graph, JSON-LD, sitemap, robots.txt, Core Web Vitals, accessibilité). À utiliser lors de la création ou modification d'une page publique, d'un template Twig de mise en page, ou quand l'utilisateur parle de SEO, Google, partage sur les réseaux, vitesse ou accessibilité.
---

# Référencement, performance, accessibilité

## Chaque page publique

- `<title>` unique (50-60 caractères) et `<meta name="description">` (140-160).
  Définis-les par des blocs Twig dans `base.html.twig` (`{% block title %}`,
  `{% block meta_description %}`) que chaque page remplit.
- `<link rel="canonical">` construit avec `url()`, jamais une URL écrite en dur.
- Open Graph et Twitter Card : `og:title`, `og:description`, `og:image` (filtre
  1200x630), `og:url`, `og:type`. C'est l'aperçu quand le site est partagé.
- Un seul `<h1>` par page, hiérarchie de titres sans saut.
- `<html lang="fr">` ; si le site devient multilingue, routes préfixées par
  `{_locale}` et balises `hreflang`.

## Données structurées (JSON-LD)

- Accueil / à propos : `Person` ou `ProfessionalService` (nom, zone desservie,
  coordonnées, réseaux sociaux en `sameAs`).
- Galerie : `ImageGallery` contenant des `ImageObject` (`contentUrl`, `creator`,
  `copyrightNotice`, `creditText`, `license` si applicable).
- Génère le JSON avec le filtre Twig `json_encode`, jamais par concaténation.

## Fichiers techniques

- `/sitemap.xml` : contrôleur avec `#[Route]` et format xml, qui liste les pages et
  galeries publiées (avec `<image:image>`), à partir de la base de données.
- `/robots.txt` : autorise tout en production, pointe vers le sitemap ; bloque tout
  hors production.
- Pages d'erreur 404 et 500 personnalisées (`templates/bundles/TwigBundle/Exception/`).

## Performance (Core Web Vitals)

- La plus grande image au-dessus de la ligne de flottaison : `fetchpriority="high"`,
  sans lazy loading, et un `<link rel="preload">` si c'est une image de fond.
- Pas de framework JS lourd : AssetMapper + Stimulus + Turbo suffisent.
- Polices : au plus deux familles, `font-display: swap`, hébergées localement.
- En production, cache HTTP sur les images (`Cache-Control` long, noms versionnés).

## Accessibilité

- Contraste suffisant du texte sur les photos (voile sombre si texte sur image).
- Navigation au clavier, focus visible, lien d'évitement « Aller au contenu ».
- Menu mobile et visionneuse utilisables au clavier et au lecteur d'écran (`aria-*`).

## Vérification

Pour une page modifiée, contrôle le HTML rendu (balises meta, JSON-LD valide) dans un
test `WebTestCase` ou dans le navigateur, et lance la skill `qualite`.
