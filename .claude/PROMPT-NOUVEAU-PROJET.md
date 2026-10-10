Crée un nouveau projet Symfony dans le dossier [CHEMIN/DU/DOSSIER] pour un [TYPE DE SITE, ex. : site vitrine d'artisan, portfolio de graphiste].
Travaille uniquement dans ce dossier : ne modifie rien dans ~/.claude ni dans mes autres projets.

1. Installation
   - Symfony 7.4 LTS dès le départ (pas la dernière version non LTS) :
     `composer create-project symfony/skeleton:"7.4.*" .` puis `composer require webapp`.
   - Vérifie avec `php bin/console about` que la version est bien 7.4 (LTS).
   - Outils qualité en dev : `phpstan/phpstan`, `phpstan/phpstan-symfony` (sans
     extension-installer, inclus l'extension dans `phpstan.dist.neon`, niveau 6,
     `containerXmlPath` sur `var/cache/dev/App_KernelDevDebugContainer.xml`) et
     `friendsofphp/php-cs-fixer` (règles `@Symfony`).
   - PHPStan et `lint:container` doivent passer sans erreur.
   - `git init -b main` et `git add -A`, sans commit (je ferai le premier commit).

2. Configuration Claude du projet (dans `.claude/` du projet uniquement)
   - Ne copie pas de `settings.json`, de hooks ou de serveurs MCP venant de dépôts
     d'exemple (ex. : shanraisshan/claude-code-best-practice) : ils autorisent
     `Bash(*)` et ajoutent des scripts à chaque événement. N'installe pas non plus
     leurs skills de démonstration (météo, heure, présentation).
   - Reprends les skills de mon projet de référence
     `/Users/seanthompson/Desktop/Pro/SiteWeb/Portfolios/testPortfolioPhotographe/.claude/skills/`
     (`qualite`, `galerie-photo`, `seo-portfolio`, `formulaire-contact`,
     `front-twig`, `mise-en-production`) et adapte-les au nouveau type de site :
     garde ce qui est générique (qualité, SEO, front, formulaire, mise en prod),
     remplace `galerie-photo` par une skill propre au métier du nouveau site.
     Chaque skill : `.claude/skills/<nom>/SKILL.md`, un nom et une description
     qui dit quand l'utiliser, contenu concret et court.
   - Complète `CLAUDE.md` (garde la ligne `@AGENTS.md` générée par Symfony) avec :
     le but du site, la stack, la liste des skills, les outils qualité, et
     « Réponds en français ».

3. Avant de coder des fonctionnalités, pose-moi les questions qui manquent
   (contenu, base de données, espace d'administration, multilingue,
   hébergement) au lieu de deviner.

À la fin, fais-moi un récapitulatif : version installée, fichiers créés, résultats
des vérifications, et ce qu'il reste à décider.
