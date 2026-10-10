---
name: qualite
description: Vérifie la qualité du code Symfony avant de déclarer une tâche terminée (lint container/Twig/YAML, PHPStan, PHP-CS-Fixer, PHPUnit). À utiliser après toute modification de code PHP, Twig ou YAML, ou quand l'utilisateur demande de vérifier, tester ou valider le projet.
---

# Contrôle qualité

Lance ces commandes depuis la racine du projet, dans cet ordre. Une tâche n'est pas
terminée tant qu'une étape échoue.

```bash
php bin/console lint:container
php bin/console lint:twig templates/
php bin/console lint:yaml config/ --parse-tags
vendor/bin/php-cs-fixer fix
vendor/bin/phpstan analyse --no-progress
php bin/phpunit
```

## Règles

- PHPStan (niveau 6, extension Symfony) lit le container compilé dans
  `var/cache/dev/`. Si PHPStan signale des services inconnus, lance d'abord
  `php bin/console cache:clear`.
- Corrige la cause d'une erreur PHPStan. Pas de `@phpstan-ignore`, pas de baseline,
  pas de cast ou de `@var` juste pour faire taire l'erreur.
- `php-cs-fixer fix` reformate les fichiers : relis le diff s'il touche des fichiers
  que tu n'as pas modifiés.
- Chaque fonctionnalité a un test qui l'utilise comme un vrai appelant :
  `WebTestCase` pour une page ou un formulaire (requête HTTP, statut, contenu,
  redirection), `KernelTestCase` pour un service.
- Si une page ne répond pas comme prévu, lis `var/log/dev.log` et le profiler
  (`/_profiler`) avant de modifier le code.

## Compte rendu

Termine par un récapitulatif court : chaque commande, OK ou échec, avec la sortie
utile en cas d'échec. Ne dis pas « tout passe » si une étape n'a pas été lancée.
