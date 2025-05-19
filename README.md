# WTFAlert - Environnement de développement Docker

Ce projet contient une configuration Docker pour développer une application Laravel avec PostgreSQL et Nginx.

## Structure des conteneurs

- **nginx**: Serveur web qui distribue les requêtes HTTP
- **app**: Application Laravel (PHP-FPM)
- **db**: Base de données PostgreSQL

## Prérequis

- Docker
- Docker Compose

## Installation et démarrage

1. Cloner ce dépôt
2. Lancer le script d'initialisation pour créer un nouveau projet Laravel:

```bash
./init_project.sh
```

Ce script va:
- Créer un nouveau projet Laravel dans le dossier WTFAlert
- Configurer les permissions nécessaires
- Démarrer les conteneurs Docker

## Utilisation

### Accéder à l'application

Une fois les conteneurs démarrés, l'application est accessible à l'adresse:
http://localhost

### Commandes utiles

- **Démarrer les conteneurs**: `docker-compose up -d`
- **Arrêter les conteneurs**: `docker-compose down`
- **Exécuter des commandes Artisan**: `docker-compose exec app php artisan [commande]`
- **Accéder à la base de données**: `docker-compose exec db psql -U wtfalert_user -d wtfalert`

## Configuration de la base de données

Les informations de connexion à la base de données sont définies dans le fichier `.env`:

- **Hôte**: db
- **Port**: 5432
- **Base de données**: wtfalert
- **Utilisateur**: wtfalert_user
- **Mot de passe**: wtfalert_password

## Personnalisation

Vous pouvez modifier les configurations:
- Nginx: `/docker/nginx/default.conf`
- PHP: `/docker/php/Dockerfile`
- Variables d'environnement: `.env`
