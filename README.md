# e-commerce_CICD_PHP (simple PHP + MySQL app)

Application de recettes en PHP natif avec authentification, CRUD de recettes, commentaires, contact et upload de capture.

## Demarrage rapide (Docker)

```bash
docker compose -f docker-compose.yml up -d --build
```

URL de l'application: `http://localhost/`

Arret:

```bash
docker compose -f docker-compose.yml down -v
```

## Variables DB

Le code lit ces variables d'environnement:

- `DB_HOST` (defaut: `db` en docker, sinon `127.0.0.1`)
- `DB_PORT` (defaut: `3306`)
- `DB_NAME` (defaut: `fooddb`)
- `DB_USER` (defaut: `app_user`)
- `DB_PASSWORD` (defaut: `app_password`)

## Tests

Unit:

```bash
docker run --rm -v "$PWD:/app" -w /app php:8.3-cli php -d assert.exception=1 tests/unit.php
```

E2E:

```bash
docker compose -f docker-compose.yml up -d --build
bash tests/e2e.sh
docker compose -f docker-compose.yml down -v
```

## Structure

- `public/`: routes HTTP (login, recettes, commentaires, contact)
- `app/Core/`: logique centralisee (DB, auth, validation, helpers, uploads)
- `app/Views/layout/`: layout commun
- `config/`: configuration
- `database/init.sql`: schema + seed
- `docker/`: Dockerfile, compose et vhost Apache
- `storage/uploads/`: fichiers uploades
- `tests/`: unit + e2e

## Flux principal

1. `/` redirige vers `/auth/login.php`
2. Login reussi -> `/recipes/home.php`
3. Depuis home: lecture, creation, edition, suppression de recette
4. Depuis detail recette: ajout de commentaire
5. Formulaire contact: validation + upload optionnel
