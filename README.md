# Application de Recettes

Application web de partage de recettes avec système d'authentification, gestion de recettes, commentaires et formulaire de contact.

## Fonctionnalités

- **Authentification** : inscription et connexion des utilisateurs
- **Recettes** : création, lecture, modification et suppression de recettes
- **Commentaires** : ajout de commentaires sur les recettes
- **Contact** : formulaire de contact avec upload de fichiers
- **Uploads** : gestion d'images pour les recettes

## Structure du projet
```
├── public/           # Pages accessibles (login, recettes, contact...)
├── app/
│   ├── Core/        # Logique centralisée (DB, auth, validation...)
│   └── Views/       # Templates et layout
├── config/          # Configuration de l'application
├── database/        # Schéma et données initiales
├── storage/         # Fichiers uploadés
└── tests/           # Tests unitaires et E2E
```

## Installation et exécution

### Prérequis
- Docker et Docker Compose

### Démarrage
```bash
# Lancer l'application
docker compose up -d --build

# Accéder à l'application
http://localhost/
```

### Arrêt
```bash
docker compose down -v
```

### Tests

**Tests unitaires :**
```bash
docker run --rm -v "$PWD:/app" -w /app php:8.3-cli php -d assert.exception=1 tests/unit.php
```

**Tests E2E :**
```bash
docker compose up -d --build
bash tests/e2e.sh
docker compose down -v
```

## Configuration

Les variables d'environnement sont définies dans `docker-compose.yml` :
- Base de données (host, port, nom, user, password)
- Configuration PHP et Apache

---

**Stack technique** : PHP 8.3, MySQL 8.0, Apache