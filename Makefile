# Makefile pour la gestion du projet WTFAlert
# Variables
DOCKER_COMPOSE = docker compose
CONTAINER_APP = app
CONTAINER_DB = db
CONTAINER_NGINX = nginx

# Commandes générales
.PHONY: help
help:
	@echo "Commandes disponibles:"
	@echo "  make init        - Initialise le projet Laravel et démarre les conteneurs"
	@echo "  make start       - Démarre les conteneurs Docker"
	@echo "  make stop        - Arrête les conteneurs Docker"
	@echo "  make restart     - Redémarre les conteneurs Docker"
	@echo "  make status      - Affiche l'état des conteneurs Docker"
	@echo "  make logs        - Affiche les logs des conteneurs Docker"
	@echo "  make clean       - Arrête et supprime les conteneurs, réseaux et volumes"
	@echo "  make reset       - Supprime tous les conteneurs, volumes et le projet Laravel"
	@echo "  make bash        - Lance un terminal bash dans le conteneur de l'application"
	@echo "  make composer    - Lance une commande composer dans le conteneur (usage: make composer cmd=install)"
	@echo "  make artisan     - Lance une commande artisan dans le conteneur (usage: make artisan cmd=migrate)"
	@echo "  make db          - Lance un terminal psql dans le conteneur de base de données"

# Initialisation du projet
.PHONY: init
init:
	@echo "Création du projet Laravel..."
	@mkdir -p WTFAlert
	@docker run --rm -v $(shell pwd)/WTFAlert:/app composer create-project --prefer-dist laravel/laravel /app
	@echo "Configuration des permissions..."
	@chmod -R 777 WTFAlert/storage WTFAlert/bootstrap/cache
	@echo "Démarrage des conteneurs Docker..."
	@$(DOCKER_COMPOSE) up -d
	@echo "----------------------------------------"
	@echo "Configuration terminée!"
	@echo "Vous pouvez accéder à votre application Laravel à l'adresse: http://localhost"
	@echo "----------------------------------------"

# Commandes Docker
.PHONY: start
start:
	@echo "Démarrage des conteneurs Docker..."
	@$(DOCKER_COMPOSE) up -d

.PHONY: stop
stop:
	@echo "Arrêt des conteneurs Docker..."
	@$(DOCKER_COMPOSE) stop

.PHONY: restart
restart:
	@echo "Redémarrage des conteneurs Docker..."
	@$(DOCKER_COMPOSE) restart

.PHONY: status
status:
	@echo "État des conteneurs Docker:"
	@$(DOCKER_COMPOSE) ps

.PHONY: logs
logs:
	@echo "Affichage des logs des conteneurs Docker:"
	@$(DOCKER_COMPOSE) logs

# Nettoyage
.PHONY: clean
clean:
	@echo "Arrêt et suppression des conteneurs, réseaux et volumes..."
	@$(DOCKER_COMPOSE) down

.PHONY: reset
reset:
	@echo "Suppression complète du projet..."
	@$(DOCKER_COMPOSE) down -v
	@echo "Suppression du dossier WTFAlert..."
	@rm -rf WTFAlert
	@echo "Projet supprimé avec succès!"

# Commandes utiles
.PHONY: bash
bash:
	@echo "Lancement d'un terminal bash dans le conteneur de l'application..."
	@$(DOCKER_COMPOSE) exec $(CONTAINER_APP) bash

.PHONY: composer
composer:
	@if [ -z "$(cmd)" ]; then \
		echo "Usage: make composer cmd=<commande>"; \
		echo "Exemple: make composer cmd=require laravel/ui"; \
	else \
		echo "Exécution de composer $(cmd)..."; \
		$(DOCKER_COMPOSE) exec $(CONTAINER_APP) composer $(cmd); \
	fi

.PHONY: artisan
artisan:
	@if [ -z "$(cmd)" ]; then \
		echo "Usage: make artisan cmd=<commande>"; \
		echo "Exemple: make artisan cmd=make:controller UserController"; \
	else \
		echo "Exécution de php artisan $(cmd)..."; \
		$(DOCKER_COMPOSE) exec $(CONTAINER_APP) php artisan $(cmd); \
	fi

.PHONY: db
db:
	@echo "Connexion au serveur PostgreSQL..."
	@$(DOCKER_COMPOSE) exec $(CONTAINER_DB) psql -U wtfalert_user -d wtfalert

# Commande par défaut
.DEFAULT_GOAL := help
