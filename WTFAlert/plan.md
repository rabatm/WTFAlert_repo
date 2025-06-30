# Plan de Développement - Administration WTFAlert

## Phase 1: Authentification et Base
- [x] Laravel est déjà installé avec Sanctum pour l'API
- [ ] Configurer l'authentification pour l'admin
- [ ] Implémenter Spatie Permissions
- [ ] Créer les rôles : 
  - [ ] Super Admin (accès complet)
  - [ ] Admin Mairie (accès à une mairie spécifique)
  - [ ] Gestionnaire (accès limité)
- [ ] Créer le layout de base de l'admin avec TailwindCSS
- [ ] Configurer la vérification d'email
- [ ] Créer le layout de base de l'admin avec TailwindCSS
- [ ] mise en place mot de passe oublié

## Phase 2: Gestion des Mairies reserver au super admin
- [ ] Créer contrôleur Mairie
- [ ] uniquement les super admin peuvent voir toutes les maires
- [ ] les admin mairie peuvent voir les mairies qu'il gerent uniquement
- [ ] Créer vues :
  - [ ] Liste des mairies pour admin mairie et superadmin
  - [ ] Formulaire création/édition pour superadmin unique
  - [ ] Vue détaillée pour superadmin
- [ ] Implémenter la recherche et le filtrage
- [ ] Créer les routes protégées

## Phase 3: Gestion des Utilisateurs
- [ ] Créer interface gestion utilisateurs
- [ ] Implémenter l'assignation de rôles
- [ ] Créer les vues de profil
- [ ] Configurer les permissions

## Phase 4: Gestion des Foyers
- [ ] Créer modèle Foyer avec relations
- [ ] Créer contrôleur Foyer
- [ ] Créer vues :
  - [ ] Liste des foyers
  - [ ] Formulaire création/édition
  - [ ] Vue détaillée
- [ ] Implémenter la recherche avancée
- [ ] Ajouter la géolocalisation

## Phase 5: Gestion des Habitants
- [ ] Créer modèle Habitant avec relations
- [ ] Créer contrôleur Habitant
- [ ] Créer vues :
  - [ ] Liste des habitants
  - [ ] Formulaire création/édition
  - [ ] Fiche détaillée
- [ ] Implémenter l'historique des modifications

## Phase 6: Système d'Alertes
- [ ] Créer modèle Alerte
- [ ] Créer contrôleur Alerte
- [ ] Créer vues :
  - [ ] Tableau des alertes
  - [ ] Détail d'une alerte
- [ ] Implémenter les statuts
- [ ] Configurer les notifications

## Phase 7: Demandes de Modification
- [ ] Créer modèle DemandeModification
- [ ] Créer contrôleur DemandeModification
- [ ] Créer vues :
  - [ ] Liste des demandes
  - [ ] Détail d'une demande
- [ ] Implémenter le workflow de validation

## Phase 8: Tableau de Bord
- [ ] Créer DashboardController
- [ ] Implémenter les widgets :
  - [ ] Statistiques
  - [ ] Activités récentes
  - [ ] Alertes en attente
- [ ] Créer les graphiques

## Phase 9: Outils d'Administration
- [ ] Créer interface d'export/import
- [ ] Implémenter la génération de rapports
- [ ] Créer la gestion des sauvegardes
- [ ] Ajouter les logs d'activité

## Phase 10: Sécurité et Tests
- [ ] Configurer les politiques d'accès
- [ ] Implémenter la journalisation
- [ ] Tester les fonctionnalités
- [ ] Faire une revue de sécurité

## Phase 11: Déploiement
- [ ] Configurer .env pour la production
- [ ] Optimiser les performances
- [ ] Mettre en place les sauvegardes
- [ ] Documenter l'administration

## Phase 12: Documentation
- [ ] Créer manuel utilisateur
- [ ] Documenter l'API
- [ ] Faire des guides d'installation
- [ ] Créer des vidéos tutoriels

## Technologies Utilisées
- **Backend** : Laravel 10.x
- **Frontend** : Blade, Livewire, Alpine.js, TailwindCSS
- **Base de données** : MySQL/PostgreSQL
- **Gestion des rôles** : Spatie Permissions
- **Déploiement** : Laravel Forge/Envoyer

## Notes
- Les tâches peuvent être réorganisées selon les priorités
- Des sous-tâches peuvent être ajoutées au fur et à mesure
- Les validations de formulaire et la gestion des erreurs doivent être implémentées à chaque étape
