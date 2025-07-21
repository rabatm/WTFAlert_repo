#!/bin/bash

# Ce script initialise un nouveau projet Laravel dans le dossier WTFAlert

# Vérifier si Docker est installé
if ! command -v docker &> /dev/null; then
    echo "Docker n'est pas installé. Veuillez l'installer avant d'exécuter ce script."
    exit 1
fi

# Vérifier si docker-compose est installé
if ! command -v docker-compose &> /dev/null; then
    echo "Docker Compose n'est pas installé. Veuillez l'installer avant d'exécuter ce script."
    exit 1
fi

# Créer un nouveau projet Laravel
echo "Création d'un nouveau projet Laravel..."
docker run --rm -v $(pwd)/WTFAlert:/app composer create-project --prefer-dist laravel/laravel /app

# Copier le fichier .env.example vers .env et générer la clé d'application
echo "Configuration du fichier .env..."
cp WTFAlert/.env.example WTFAlert/.env

# Générer la clé d'application Laravel
echo "Génération de la clé d'application..."
docker-compose up -d
docker-compose exec app php artisan key:generate

# Ajuster les permissions
echo "Ajustement des permissions..."
chmod -R 777 WTFAlert/storage WTFAlert/bootstrap/cache

# Message de confirmation
echo "----------------------------------------"
echo "Configuration terminée!"
echo "Vous pouvez accéder à votre application Laravel à l'adresse: http://localhost"
echo "----------------------------------------"
echo "Pour exécuter des commandes Laravel, utilisez: docker-compose exec app php artisan [commande]"
echo "Pour arrêter les conteneurs, utilisez: docker-compose down"
echo "----------------------------------------"