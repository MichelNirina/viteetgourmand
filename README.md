# Vite & Gourmand

Application de commande en ligne pour un traiteur événementiel basé à Bordeaux.

## Stack technique

- **Frontend** : HTML5, CSS3, JavaScript Vanilla (ES Modules)
- **Backend** : PHP 8+ avec PDO (architecture REST API)
- **Base de données** : MySQL
- **Serveur local** : XAMPP (Apache + MySQL)

## Prérequis

- [XAMPP](https://www.apachefriends.org/) installé (Apache + MySQL)
- PHP 8.0 ou supérieur

## Installation en local

### 1. Cloner le dépôt

Placer le projet dans le dossier `htdocs` de XAMPP :

```bash
cd C:/xampp/htdocs
git clone https://github.com/MichelNirina/viteetgourmand.git
```

Le projet doit se trouver à l'emplacement : `C:/xampp/htdocs/viteetgourmand`

### 2. Démarrer XAMPP

Lancer **Apache** et **MySQL** depuis le panneau de contrôle XAMPP.

### 3. Créer la base de données

1. Ouvrir [phpMyAdmin](http://localhost/phpmyadmin)
2. Créer une base de données nommée **`viteetgourmand2`**
3. Sélectionner cette base, aller dans l'onglet **Importer**
4. Importer le fichier : `backend/sql/database.sql`

### 4. Accéder à l'application

| URL | Description |
|-----|-------------|
| `http://localhost/viteetgourmand/frontend/` | Page d'accueil |
| `http://localhost/viteetgourmand/frontend/pages/login.html` | Connexion |
| `http://localhost/viteetgourmand/backend/public/` | API REST |

## Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@viteetgourmand.fr | admin123 |
| Employé | employe@viteetgourmand.fr | employe123 |
| Client | *(créer un compte via l'inscription)* | — |

## Structure du projet

```
viteetgourmand/
├── backend/
│   ├── config/         # Configuration base de données
│   ├── controllers/    # Contrôleurs REST (retournent du JSON)
│   ├── core/           # Router, ApiController, Model de base
│   ├── models/         # Modèles PDO
│   ├── public/         # Point d'entrée API (index.php)
│   └── sql/            # Fichier SQL de création et données
└── frontend/
    ├── assets/
    │   ├── css/        # Feuilles de style
    │   └── images/     # Images statiques et photos de plats
    ├── js/
    │   ├── components/ # Composants réutilisables (navbar, footer, layout)
    │   ├── pages/      # Logique de chaque page
    │   │   ├── admin/      # Pages espace administrateur
    │   │   ├── client/     # Pages espace client
    │   │   ├── employee/   # Pages espace employé
    │   │   └── commande/   # Tunnel de commande
    │   ├── services/   # Couche API (fetch centralisé)
    │   └── utils/      # Utilitaires (auth, helpers, config)
    └── pages/          # Fichiers HTML (squelettes)
```

## Rôles et accès

| Rôle | Accès |
|------|-------|
| **Client** | Consulter les menus, passer commande, suivre ses commandes, laisser un avis |
| **Employé** | Gérer commandes, menus, plats, allergènes, avis, horaires |
| **Administrateur** | Tous les accès employé + gestion des utilisateurs, employés, thèmes, régimes, statistiques |

## Architecture

Le projet suit une architecture **découplée** :

- Le **backend** expose une API REST (JSON uniquement) via `backend/public/index.php`
- Le **frontend** consomme cette API avec `fetch()` et construit le HTML dynamiquement côté client
- L'authentification repose sur les **sessions PHP** (`credentials: 'include'` côté JS)
