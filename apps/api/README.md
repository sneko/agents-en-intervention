# Agent en intervention - API

[![Minimum PHP version](https://img.shields.io/badge/php-%3E%3D8.2-%23777BB4?logo=php&style=flat)](https://www.php.net/)
[![pipeline status](https://gitlab.com/incubateur-territoires/startups/agents-intervention/api/badges/main/pipeline.svg)](https://gitlab.com/incubateur-territoires/startups/agents-intervention/api/-/commits/main)

## Installation

Télécharger le projet :

```shellsession
user@host ~$ cd [CHEMIN_OU_METTRE_LE_PROJET] # Exemple : ~/projets/
user@host projets$ git clone https://gitlab.com/incubateur-territoires/startups/agents-intervention/api.git
user@host projets$ cd api
```

### Construire l'image

Définir l'utilisateur et le mot de passe pour la base de données dans les fichiers
`./database/.user` et `./database/.password` respectifs (exemple : `./database/.user.dist`)
et construire les images :

```shellsession
user@host api$ docker compose build
```

### Ajouter les variables d'environnement

| Nom de la variable | Valeur par défaut                                                        | Exemple de valeur                                                   | Présente dans le fichier | Documentation                                                                                                                                              |
| ------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------- | ------------------------ | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| APP_ENV            | dev                                                                      | dev / prod / test                                                   | ./.env                   | [doc](https://symfony.com/doc/current/configuration.html#configuration-environments)                                                                       |
| APP_SECRET         | 797cd8b0c82fa3e7f5bd09f60b4650b1                                         | 797cd8b0c82fa3e7f5bd09f60b4650b1                                    | ./.env                   | [doc](https://symfony.com/doc/current/reference/configuration/framework.html#secret) [secrets](https://symfony.com/doc/current/configuration/secrets.html) |
| DATABASE_URL       | "postgresql://USER:PASSWORD@HOST:5432/api?serverVersion=15&charset=utf8" | "postgresql://root:root@api:5432/api?serverVersion=15&charset=utf8" | ./.env                   |                                                                                                                                                            |
| CORS_ALLOW_ORIGIN  | '^https?://(localhost\|127\\.0\\.0\\.1)(:[0-9]+)?$'                      | '^https?://(localhost\|127\\.0\\.0\\.1)(:[0-9]+)?$'                 | ./.env                   |                                                                                                                                                            |
| JWT_SECRET_KEY     | %kernel.project_dir%/config/jwt/private.pem                              | %kernel.project_dir%/config/jwt/private.pem                         | ./.env                   |                                                                                                                                                            |
| JWT_PUBLIC_KEY     | %kernel.project_dir%/config/jwt/public.pem                               | %kernel.project_dir%/config/jwt/public.pem                          | ./.env                   |                                                                                                                                                            |
| JWT_PASSPHRASE     | 663b89c05f203d77642c97a1c228d02ff90b31b904629bbaccfb96985b981b36         | 663b89c05f203d77642c97a1c228d02ff90b31b904629bbaccfb96985b981b36    | ./.env                   |                                                                                                                                                            |
| S3_ENDPOINT        |                                                                          | http://192.168.1.2:3003                                             | ./.env                   | L'adresse du serveur S3/Minio.                                                                                                                             |
| S3_ACCESS_KEY      |                                                                          | 00000000000000000000                                                | ./.env                   | La clé d'accès du serveur S3/Minio.                                                                                                                        |
| S3_SECRET_KEY      |                                                                          | 00000000-0000-0000-0000-000000000000                                | ./.env                   | La clé secrète du serveur S3/Minio.                                                                                                                        |
| S3_REGION          |                                                                          | fr-par                                                              | ./.env                   | La région du serveur S3/Minio.                                                                                                                             |
| S3_NAME            |                                                                          | aei                                                                 | ./.env                   | Le bucket du serveur S3/Minio.                                                                                                                             |
| TZ                 | Europe/Paris                                                             | Europe/Paris                                                        | ./.env                   | Nécessaire pour définir le fuseau horaire du conteneur.                                                                                                    |

En production, il existe certaines [recommandations](https://symfony.com/doc/current/configuration.html#configuring-environment-variables-in-production).

### Installer les dépendences PHP

Définir la configuration de la base de données pour API Platform (voir `./.env`)
et installer les dépendences PHP :

```shellsession
user@host api$ docker compose run --rm api composer install -o [--no-dev]
```

L'option "--no-dev" est pour l'environnement de production.

Pour l'environnement de développement et de test seulement :

```shellsession
user@host api$ docker compose run --rm api phive install --trust-gpg-keys 4AA394086372C20A,C5095986493B4AA0,12CE0F1D262429A5,67F861C3D889C656,31C7E470E2138192
```

### Créer les clés de sécurité pour le jeton d'authentification

Il est nécessaire d'être authentifié pour utiliser les routes de l'API.
Ceci se fait en envoyant un identifiant (login) et un mot de passe (password) à la route "POST /authentication".
Si l'utilisateur réussit à s'authentifier, l'API génère un jeton (JWT) et le renvoie.
Ce jeton sera à utiliser pour les autres requêtes à l'API.
Les jetons sont générés à partir de clés de sécurité qu'il faut créer avec la commande :

```shellsession
user@host api$ docker compose run --rm api bin/console lexik:jwt:generate-keypair
```

### Créer la base de données

```shellsession
user@host api$ docker compose run --rm api ./bin/console doctrine:database:create [-e test]
user@host api$ docker compose run --rm api ./bin/console make:migration [-e test]
user@host api$ docker compose run --rm api ./bin/console doctrine:migrations:migrate [--no-interaction] [-e test]
```

L'option "-e test" est pour l'environnement de test qui utilise Sqlite.

### Ajouter des données aléatoires (fixtures)

Après avoir créé la base de données, il est possible d'ajouter des données aléatoires via :

```shellsession
user@host api$ docker compose run --rm api bin/console hautelook:fixtures:load
```

## Utilisation

Une fois l'installation terminée, il est possible de démarrer les conteneurs avec :

```shellsession
user@host api$ docker compose up -d
```

L'api sera disponible dans le navigateur via : http://localhost:3002/

Pour arrêter les conteneurs :

```shellsession
user@host api$ docker compose down
```

## Développement

Pour ajouter des commandes/alias au conteneur via un fichier `./.ashrc`,
on peut copier le fichier exemple `./.ashrc.dist` :

```shellsession
user@host api$ cp ./.ashrc.dist ./.ashrc
```

Et ensuite, le monter dans le conteneur grâce au fichier `./docker-compose.override.yml`
à créer :

```yaml
services:
  api:
    volumes:
      - ./.ashrc:/root/.ashrc
```

Les fichiers `./.ashrc` et `./docker-compose.override.yml` sont ignorés par git.

### Commandes/alias par défaut

Le fichier `./.ashrc.dist` contient les éléments suivants :

- `migrate` : pour créer les migrations;
- `migratetest` : pour créer les migrations de test;
- `fixture` : pour ajouter les fixtures à la base de données;
- `psalm` : pour exécuter Psalm (analyse statique);
- `phpunit` : pour exécuter PHPUnit (test);
- `infection` : pour exécuter Infection (mutation de code);
- `phpdoc` : pour exécuter PHPDoc (documentation de code);
- `phpcbf` : pour indenter le code à la norme PSR-12;
- `phpcs` : pour vérifier l'indentation du code à la norme PSR-12;
- `ci` : pour exécuter les outils d'intégration continue.

## Intégration continue

### Tests

Pour exécuter les tests :

```shellsession
user@host api$ docker compose run --rm api ./tools/phpunit -c ./ci/phpunit.xml
```

La commande va générer des fichiers dans `./ci/phpunit/`.
Le fichier `./ci/phpunit/html/index.html` montre la couverture de code
et `./ci/phpunit/testdox.html` affiche une liste détaillée des tests qui passent / échouent.

Pour exécuter les tests de mutation,
il faut au préalable effectuer la migration de la base de données de test, puis :

```shellsession
user@host api$ docker compose run --rm api ./tools/infection -c./ci/infection.json
```

Le rapport HTML sera généré dans `./ci/infection/`.

### Analyse statique

Pour faire une analyse statique :

```shellsession
user@host api$ docker compose run --rm api ./tools/psalm -c ./ci/psalm.xml [--report=./psalm/psalm.txt --output-format=text]
```

Il faut utiliser "--report=./psalm/psalm.txt --output-format=text"
pour avoir le rapport dans un fichier plutôt qu'à l'écran.

### Documentation PHP

Pour générer la documentation PHP :

```shellsession
user@host api$ docker compose run --rm api ./tools/phpDocumentor --config ./ci/phpdoc.xml
```

Pour consulter la documentation HTML, il faut ouvrir le fichier `./ci/phpdoc/index.html`.

### Standard

Les fichiers PHP de ce projet suivent la norme [PSR-12](https://www.php-fig.org/psr/psr-12/).
Il est possible d'indenter le code avec :

```shellsession
user@host api$ docker compose run --rm api ./tools/phpcbf --standard=PSR12 --extensions=php --ignore=./src/Kernel.php,./tests/bootstrap.php -p ./src/ ./tests/
```

## Documentation

La documentation se trouve dans le dossier `./resources/documentation/`.

## Licence

Ce projet est sous licence [MIT](./LICENSE).
