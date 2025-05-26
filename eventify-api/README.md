# Eventify API - Plateforme de gestion d'événements

## Description

Eventify est une API RESTful développée avec Laravel, destinée à alimenter une plateforme web et mobile de gestion complète d'événements.  
Cette API permet la gestion des utilisateurs, des événements, des catégories, ainsi que des réservations. Elle intègre également un système d'authentification sécurisé.

---

## Fonctionnalités principales

- Inscription et authentification des utilisateurs
- Gestion des profils utilisateurs
- Création, modification et suppression d'événements avec catégories dynamiques
- Recherche, filtrage et pagination des événements
- Réservation de places pour des événements
- Visualisation et gestion des réservations (utilisateurs et administrateurs)
- API sécurisée avec validation et gestion des erreurs
- Tests automatisés unitaires et fonctionnels

---

## Apports et Difficultés rencontrées

### Apports

- Mise en pratique complète d'un projet API Laravel complexe avec gestion des relations entre modèles
- Application des bonnes pratiques de développement backend : validation, sécurisation, tests, structuration
- Expérience concrète de la pagination, filtres avancés, authentification API token
- Rédaction de tests fonctionnels robustes garantissant la fiabilité de l’API

### Difficultés rencontrées

- Comprendre et gérer correctement l’authentification API dans les tests (notamment les erreurs 401)
- Concevoir des requêtes de filtrage et pagination efficaces
- Gérer les relations complexes (ex: réservation liée à un utilisateur et un événement)
- Assurer la cohérence des données et gérer les erreurs de validation

---

## Choix techniques

- **Framework** : Laravel 10, pour sa robustesse, sa communauté active et son écosystème riche (Eloquent ORM, Auth, Validation)
- **Base de données** : MySQL en développement, SQLite pour les tests automatisés, pour un environnement simple et efficace
- **Authentification** : Sanctum (ou Passport selon l'implémentation), pour une gestion sécurisée des tokens API
- **Tests** : PHPUnit, tests unitaires et fonctionnels pour valider le comportement des routes et la logique métier
- **Pagination & filtres** : Utilisation des méthodes intégrées d'Eloquent et Query Builder pour la simplicité et la performance
- **Gestion des erreurs** : Validation via FormRequest, réponses JSON claires avec codes HTTP appropriés

---

## Ressources utilisées

- Documentation Laravel : https://laravel.com/docs/10.x
- Documentation Laravel Sanctum : https://laravel.com/docs/10.x/sanctum
- Tutoriels Laravel API et authentification (Laracasts, YouTube)
- PHPUnit official docs : https://phpunit.de/manual/current/en/
- Forums et Stack Overflow pour résoudre des problèmes spécifiques
- Outils Postman pour tests manuels des endpoints

---

## Installation & Exécution

```bash
git clone <repo-url>
cd eventify-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
