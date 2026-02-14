# ECF Vite & Gourmand

## Présentation
Application web de commande de menus pour un restaurant.

## Technologies
- PHP
- MySQL / MariaDB
- Bootstrap 5
- Git & GitHub

## Installation en local
1. Cloner le dépôt : `git clone https://github.com/MichelNirina/viteetgourmand.git`
2. Copier dans `htdocs` de XAMPP
3. Importer la base SQL via phpMyAdmin
4. Configurer `config.php` avec vos identifiants DB
5. Lancer `http://localhost/vite_gourmand` dans le navigateur

## Branches Git
- `main` : version stable / production  
- `develop` : version de développement

## Création d’une base de données avec phpMyAdmin (XAMPP)
- Vérifier que Apache et MySQL sont démarrés dans XAMPP
- Accéder à phpMyAdmin via : http://localhost/phpmyadmin
- Cliquer sur Nouvelle base de données
- Saisir le nom exact : vite_gourmand
- Cliquer sur la base de données vite_gourmand (menu à gauche)
- Cliquer sur l’onglet Importer
- Cliquer sur Choisir un fichier
- Sélectionner le fichier vitegourmanddb.sql
- Vérifier que le format est bien SQL
- Cliquer sur Exécuter
