# e-commerce_CICD_PHP (site de recettes + CI/CD)

Petit projet PHP/MySQL orienté apprentissage :
- authentification (login/logout)
- CRUD recettes + commentaires
- stack Docker (PHP Apache + MySQL + phpMyAdmin)
- pipeline CI GitHub Actions (unit + e2e via Docker)

## Démarrage rapide (Docker)

Lancer la stack :
```bash
docker compose -f docker/docker-compose.yml up -d --build
```

URLs :
- App : `http://localhost/` (redirige vers `/login/login.php`)
- phpMyAdmin : `http://localhost:8080/`

Arrêter / nettoyer :
```bash
docker compose -f docker/docker-compose.yml down -v
```

## Base de données

- Initialisation automatique via `sql/init.sql` (monté dans le conteneur MySQL).
- Configuration via variables d’environnement (voir `configuration/mysql.php`) :
  - `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_NAME`, `MYSQL_USER`, `MYSQL_PASSWORD`

Note : en Docker Compose, `MYSQL_HOST` doit être `db` (le service MySQL).

## Tests (CI / local)

Unit tests (sans lib externe) :
```bash
docker run --rm -v "$PWD:/app" -w /app php:8.3-cli php -d assert.exception=1 tests/unit.php
```

E2E (stack up + scripts) :
```bash
docker compose -f docker/docker-compose.yml up -d --build
bash tests/e2e.sh
docker compose -f docker/docker-compose.yml down -v
```

Sur Windows, `tests/e2e.sh` se lance via WSL ou Git Bash (besoin d’un shell bash).

## Structure du projet

- `index.php` : point d’entrée (redirige vers le login)
- `login/` : pages d’authentification (form, submit, logout)
- `application/` : fonctionnalités principales (recettes, commentaires, contact)
- `base/` : layout commun (header/footer)
- `configuration/` : config MySQL + connexion PDO
- `variables/` : fonctions utilitaires globales
- `sql/` : init/seed de la base
- `docker/` : Dockerfile + docker-compose
- `tests/` : tests unitaires + e2e
- `static/`, `final/` : assets/ressources (selon pages)

## Principes de dev (résumé)

- 1 feature = 1 branche (`feature/...`, `fix/...`, `refactor/...`).
- Ne pas casser l’existant : comprendre → modifier petit → tester.
- DRY : réutiliser/factoriser avant de copier-coller.
- Sécurité : `prepare()`/`execute()`, `htmlspecialchars()`, validation des inputs.

Pour les règles complètes, voir `AGENTS.md` (et `agent.md`).

## Containerisation / DevOps

- `docker/dockerfile` : image `php:apache` + extensions `pdo`/`pdo_mysql`.
- `docker/docker-compose.yml` : services `dev` (PHP+Apache), `db` (MySQL), `phpmyadmin`.
- `.github/workflows/ci.yml` :
  - lance `tests/unit.php` dans `php:8.3-cli`
  - build/boot la stack Docker
  - exécute `tests/e2e.sh`

## Sauvegarde / restauration d’un volume Docker

Sauvegarde d’un volume :
```bash
docker run --rm -v NOM_VOLUME:/data -v "$(pwd):/backup" alpine tar czf /backup/sauvegarde.tar.gz -C /data .
```

Restauration :
```bash
docker run --rm -v NOM_VOLUME:/data -v "$(pwd):/backup" alpine tar xzf /backup/sauvegarde.tar.gz -C /data
```
