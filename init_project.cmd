@echo off
REM Ce script initialise un nouveau projet Laravel dans le dossier WTFAlert

REM Vérifier si Docker est installé
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker n'est pas installé. Veuillez l'installer avant d'exécuter ce script.
    exit /b 1
)

REM Vérifier si docker-compose est installé
docker-compose --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker Compose n'est pas installé. Veuillez l'installer avant d'exécuter ce script.
    exit /b 1
)

REM Créer un nouveau projet Laravel
echo Création d'un nouveau projet Laravel...
docker run --rm -v %cd%/WTFAlert:/app composer create-project --prefer-dist laravel/laravel /app

REM Configuration du fichier .env
echo Configuration du fichier .env...
if exist "WTFAlert\.env.example" (
    copy "WTFAlert\.env.example" "WTFAlert\.env"
    echo Fichier .env créé à partir de .env.example
) else (
    echo Attention: .env.example non trouvé, création d'un .env minimal...
    (
    echo APP_NAME=WTFAlert
    echo APP_ENV=local
    echo APP_KEY=
    echo APP_DEBUG=true
    echo APP_URL=http://localhost
    echo.
    echo LOG_CHANNEL=stack
    echo LOG_DEPRECATIONS_CHANNEL=null
    echo LOG_LEVEL=debug
    echo.
    echo DB_CONNECTION=mysql
    echo DB_HOST=db
    echo DB_PORT=3306
    echo DB_DATABASE=wtfalert
    echo DB_USERNAME=root
    echo DB_PASSWORD=root
    echo.
    echo BROADCAST_DRIVER=log
    echo CACHE_DRIVER=file
    echo FILESYSTEM_DISK=local
    echo QUEUE_CONNECTION=sync
    echo SESSION_DRIVER=file
    echo SESSION_LIFETIME=120
    ) > "WTFAlert\.env"
)

REM Démarrer les conteneurs
echo Démarrage des conteneurs Docker...
docker-compose up -d

REM Attendre que la base de données soit prête
echo Attente de la base de données...
timeout /t 10 /nobreak >nul

REM Générer la clé d'application Laravel
echo Génération de la clé d'application...
docker-compose exec app php artisan key:generate

REM Exécuter les migrations
echo Exécution des migrations de base de données...
docker-compose exec app php artisan migrate --force

REM Exécuter le seeding
echo Exécution du seeding de la base de données...
docker-compose exec app php artisan db:seed --force

echo ----------------------------------------
echo Configuration terminée!
echo Vous pouvez accéder à votre application Laravel à l'adresse: http://localhost
echo ----------------------------------------
echo Base de données migrée et seedée avec succès!
echo ----------------------------------------
echo Pour exécuter des commandes Laravel, utilisez: docker-compose exec app php artisan [commande]
echo Pour arrêter les conteneurs, utilisez: docker-compose down
echo ----------------------------------------