# Touche pas au klaxon

Application intranet de covoiturage conçue pour optimiser les déplacements professionnels entre les différentes agences de l'entreprise.

## Description du projet

"Touche pas au klaxon" est un outil web collaboratif permettant de recenser et partager les trajets prévus. L'objectif est de réduire les déplacements à faible taux d'occupation en favorisant le covoiturage interne.

## Architecture technique

- **Architecture :** MVC (Modèle-Vue-Contrôleur)
- **Langage :** PHP 
- **Base de données :** MySQL / MariaDB
- **Style :** Bootstrap/Sass (SCSS)
- **Outils de tests :** PHPStan et PHPUnit 

## Installation

### Prérequis

- PHP 
- Serveur MySQL ou MariaDB
- Composer installé

### Étapes d'installation

1. **Cloner le projet :**

   ```bash
   git clone https://github.com/Oceane-15/touche_pas_au_klaxon
   cd touche_pas_au_klaxon

2. **Dépendances :**

Installer les dépendances PHP via Composer : composer install

3. **Base de données :**

Afin que l'application puisse accéder aux données, il faut importer la structure des dossiers /sql "database_create.sql" et "database_seed.sql" au sein de l'environnement de gestion de la base de données (phpMyAdmin).

4. **SASS :**

Le projet utilise le compilateur Sass (Live Sass Compiler) configuré pour générer automatiquement le fichier theme.css depuis theme.scss

5. **Lancement du serveur :**

php -S localhost:8000
L'application est disponible sur http://localhost:8000

6. **Analyse du code (PHPStan) :**

vendor/bin/phpstan analyse

7. **Tests unitaires (PHPUnit) :**

vendor/bin/phpunit

8. **Compte démo :**

(Admin) alexandre.martin@email.fr ; mdp : password123 
(User) sophie.dubois@email.fr ; mdp : password123
