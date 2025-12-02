# 📚 EasyBTS - Lycée Fulbert (V1) : Documentation Technique

## Guide d'Installation Point-Par-Point

Ce document est le guide de référence complet pour installer, configurer et lancer l'application EasyBTS, en fournissant toutes les étapes d'infrastructure et de code.

-----

## 1\. 🔍 Contexte du Projet et Stack Technologique

### 1.1. Objectifs Fonctionnels

L'application EasyBTS est une plateforme web visant à dématérialiser le processus d'admission au BTS SIO.

* **Sécurité et Suivi** : Assurer l'intégrité des données et permettre le suivi précis du statut de chaque dossier (`en_attente`, `valide`, `refuse`).
* **Accessibilité** : Offrir une interface conforme aux normes de l'État (via le DSFR) pour les candidats et le personnel administratif.
* **Efficacité** : Fournir des outils d'administration (filtration, export CSV) pour rationaliser le travail du secrétariat.

### 1.2. Architecture Logicielle et Outils

Le projet est basé sur une stack **Symfony** moderne, garantissant la robustesse et la maintenabilité.

| Composant | Rôle Précis | Justification Technique |
| :--- | :--- | :--- |
| **Backend** | **Symfony 6+ (PHP 8.1+)** | Fournit le routing, le conteneur de services et la structure MVC. |
| **ORM** | **Doctrine** | Gère la persistance des données et le mappage Entité-Table, évitant le SQL natif. |
| **SGBD** | **MySQL / MariaDB** | Stockage relationnel des données d'inscription. |
| **Frontend** | **DSFR (npm)** | Assure la conformité graphique aux standards de l'administration française (accessibilité et identité). |

-----

## 2\. 🛡️ Modèle de Données et Sécurité Logique

### 2.1. Structure du Dossier (Entités Centrales)

L'intégrité de chaque candidature repose sur l'entité centrale `Etudiant`.

* L'entité **`Etudiant`** est liée à toutes les informations annexes par des relations **One-to-One** (1:1), assurant qu'il n'y a qu'une seule adresse, un seul jeu de documents, et un seul compte utilisateur par candidat.
* L'utilisation des **Migrations Doctrine** garantit que la structure de la base de données est toujours synchronisée avec les classes **Entité** du code source.

### 2.2. Gestion des Rôles et Authentification

La sécurité est gérée par le composant **Symfony Security**.

* **`ROLE_USER`** : Rôle attribué aux candidats pour l'accès aux formulaires et au suivi de leur propre dossier.
* **`ROLE_ADMIN`** : Rôle attribué au personnel pour l'accès au tableau de bord de gestion et aux actions d'export/validation.

-----

## 3\. 🛠️ Guide d'Installation Point-Par-Point (6 Étapes)

Ce guide est un chemin d'exécution séquentiel, assumant que vous partez d'une machine de développement propre (hôte) avec les prérequis installés.

### 3.0. Prérequis Système

Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre machine :

| Outil | Version Minimale | Extensions/Packages Requis |
| :--- | :--- | :--- |
| **PHP** | 8.1+ | `pdo_mysql`, `intl`, `xml`, `mbstring`, `curl`, `zip` |
| **Composer** | 2.0+ | Gestionnaire de dépendances PHP |
| **Node.js & npm** | 16+ | Pour l'installation du DSFR |
| **MySQL/MariaDB** | 5.7+ / 10.3+ | Serveur de base de données |
| **Symfony CLI** | Dernière version | (Optionnel mais recommandé) |
| **Git** | 2.0+ | Pour cloner le dépôt |

**Vérification rapide des versions :**
```bash
php -v
composer -V
node -v
npm -v
mysql --version
symfony version  # Si installé
```

### 3.1. Étape 1 : Mise en Place de l'Infrastructure du Serveur BDD

Le serveur de base de données doit être créé et configuré pour être accessible depuis la machine hôte.

#### 3.1.1. Choix et Initialisation du Serveur SGBD

* **Si VM (Virtual Machine)** : Créez une VM (ex: Ubuntu), installez le paquet `mysql-server` et assurez-vous que le **port 3306 est ouvert** et accessible depuis l'IP de votre machine hôte.
* **Si Docker (Recommandé en Dev)** : Lancez un conteneur MariaDB/MySQL.
* **Si Local** : Utilisez le serveur MySQL/MariaDB installé localement (XAMPP, WAMP, ou installation native).

#### 3.1.2. Création de l'Utilisateur de l'Application

Connectez-vous à la console MySQL de votre serveur BDD pour créer l'utilisateur qui sera utilisé par Symfony.

| Commande MySQL | Objectif Précis |
| :--- | :--- |
| `CREATE USER 'easybts_user'@'%' IDENTIFIED BY 'password_fort';` | Crée l'utilisateur **`easybts_user`** pour la connexion à distance. (`%` permet l'accès depuis n'importe quelle IP, à adapter selon vos besoins de sécurité). |
| `GRANT ALL PRIVILEGES ON easybts_db.* TO 'easybts_user'@'%';` | Donne tous les droits nécessaires à Doctrine pour créer et manipuler la base de données `easybts_db`. |
| `FLUSH PRIVILEGES;` | Active les nouvelles permissions immédiatement. |

### 3.2. Étape 2 : Récupération du Code et Dépendances

Ces commandes sont exécutées sur votre **machine hôte** (poste de développement).

| Action | Commande | Explication Détaillée |
| :--- | :--- | :--- |
| **Clonage du Dépôt** | `git clone https://github.com/Wilou-36/Inscription easybts` | **Clône le dépôt** du projet dans le dossier `easybts`. |
| **Accès au Projet** | `cd easybts` | Se positionne dans le répertoire racine du projet. Toutes les commandes suivantes sont exécutées d'ici. |
| **Dépendances PHP** | `composer install` | Lit le fichier `composer.lock` et télécharge précisément toutes les dépendances Backend (Symfony, Doctrine). |
| **Dépendances Frontend** | `npm install @gouvfr/dsfr` | Installe le paquet du **Design System de l'État Français (DSFR)** pour les composants visuels. |

### 3.3. Étape 3 : Configuration du Lien BDD et Variables d'Environnement

Cette étape connecte le code au serveur BDD et configure les paramètres essentiels de l'application.

1. **Configuration du Fichier `.env.local`** :

   * Créez le fichier **`.env.local`** à la racine du projet (si non existant).
   * Définissez les variables suivantes en adaptant les valeurs à votre environnement :

```env
# Configuration de l'environnement
APP_ENV=dev
APP_SECRET=CHANGEZ_CETTE_CLE_SECRETE_PAR_UNE_CHAINE_ALEATOIRE

# Configuration de la base de données
# Format : mysql://USER:PASSWORD@HOST_IP:PORT/DB_NAME
DATABASE_URL="mysql://easybts_user:password_fort@127.0.0.1:3306/easybts_db?serverVersion=8.0"

# Configuration du mailer (pour les notifications par email)
MAILER_DSN=smtp://localhost:1025
# En production, utilisez un service SMTP réel comme :
# MAILER_DSN=smtp://user:pass@smtp.example.com:465
```

2. **Création de la Base de Données Logique** :

   * Se connecte au serveur SGBD et envoie l'instruction `CREATE DATABASE easybts_db;`.

```bash
php bin/console doctrine:database:create
```

3. **Création des Tables (Schéma)** :
   * Exécute toutes les migrations Doctrine pour créer toutes les tables et colonnes basées sur les Entités du projet.

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

### 3.4. Étape 4 : Initialisation du Schéma et Insertion des Données de Test 📝

Pour le développement, nous créons d'abord le schéma complet, puis injectons 15 dossiers de test, incluant tous les scénarios de statut possibles.

1. **Exécution du Script de Création du Schéma** :

   * Le fichier **`script_bdd.sql`** contient toutes les instructions CREATE TABLE, INDEX, VIEWS, PROCEDURES, et TRIGGERS.

```bash
mysql -u easybts_user -p easybts_db < script_bdd.sql
```

2. **Insertion des Données de Test** :

   * Le fichier **`jeu_donnees.sql`** contient 15 dossiers complets avec différents statuts.
   * Cette commande **vide les tables existantes** (`TRUNCATE TABLE`) avant d'insérer les nouvelles données.

```bash
mysql -u easybts_user -p easybts_db < jeu_donnees.sql
```

**Comptes de Test Pré-chargés :**

| Rôle | Identifiant | Mot de Passe | Statut dans la BDD |
| :--- | :--- | :--- | :--- |
| **Administrateur** | `admin@fulbert.fr` | `password` | `ROLE_ADMIN` |
| **Étudiant (Validé)** | `samir.elhassani@test.com` | `password` | `statut: valide` |
| **Étudiant (Refusé)** | `marc.legrand@test.com` | `password` | `statut: refuse` |
| **Étudiant (En attente)** | `lea.bertrand@test.com` | `password` | `statut: en_attente` |

### 3.5. Étape 5 : Compilation des Assets Frontend (Optionnel)

Si le projet utilise Webpack Encore pour gérer les assets :

```bash
npm run build
# Ou en mode développement avec watch :
npm run watch
```

### 3.6. Étape 6 : Démarrage du Serveur et Validation Finale

L'application est maintenant entièrement configurée et prête à être exécutée.

1. **Lancement du Serveur** :

```bash
# Avec Symfony CLI (recommandé)
symfony server:start

# Ou avec le serveur PHP intégré
php -S 127.0.0.1:8000 -t public/
```

2. **Accès à l'Application** :
   * Ouvrez votre navigateur à l'adresse : **`https://127.0.0.1:8000/`** (ou `http://127.0.0.1:8000/` selon votre configuration)

**Validation Finale :**

* **Test Admin** : Connectez-vous avec `admin@fulbert.fr` / `password` et vérifiez que le tableau de bord affiche les **15 dossiers** chargés.
* **Test Utilisateur** : Connectez-vous avec `samir.elhassani@test.com` / `password` pour vérifier l'affichage du statut `valide`.

-----

## 4\. 📁 Structure du Projet

```
easybts/
├── assets/              # Fichiers frontend (JS, CSS)
├── bin/                 # Scripts exécutables (console)
├── config/              # Fichiers de configuration Symfony
├── migrations/          # Migrations Doctrine
├── public/              # Point d'entrée web (index.php)
│   └── uploads/         # Fichiers uploadés par les utilisateurs
├── src/                 # Code source PHP
│   ├── Controller/      # Contrôleurs (logique métier)
│   ├── Entity/          # Entités Doctrine (modèle de données)
│   ├── Form/            # Formulaires Symfony
│   └── Repository/      # Repositories Doctrine
├── templates/           # Templates Twig (vues)
├── tests/               # Tests automatisés
├── var/                 # Fichiers temporaires (cache, logs)
├── vendor/              # Dépendances Composer
├── script_bdd.sql       # Script de création du schéma
├── jeu_donnees.sql      # Données de test
├── .env                 # Configuration par défaut
├── .env.local           # Configuration locale (à créer)
├── composer.json        # Dépendances PHP
└── README.md            # Ce fichier
```

-----

## 5\. 🔧 Commandes Utiles

### Gestion de la Base de Données
```bash
# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Créer une nouvelle migration
php bin/console make:migration

# Vérifier le schéma
php bin/console doctrine:schema:validate
```

### Gestion du Cache
```bash
# Vider le cache
php bin/console cache:clear

# Vider le cache en production
php bin/console cache:clear --env=prod
```

### Tests
```bash
# Exécuter les tests
php bin/phpunit

# Exécuter les tests avec couverture
php bin/phpunit --coverage-html var/coverage
```

### Assets
```bash
# Compiler les assets en mode développement
npm run build

# Compiler et surveiller les changements
npm run watch

# Compiler pour la production
npm run build --production
```

-----

## 6\. 🐛 Dépannage Courant

### Erreur de Connexion à la Base de Données
**Symptôme** : `SQLSTATE[HY000] [2002] Connection refused`

**Solutions** :
- Vérifiez que le serveur MySQL est démarré
- Vérifiez les identifiants dans `.env.local`
- Vérifiez que le port 3306 est accessible
- Testez la connexion : `mysql -u easybts_user -p -h 127.0.0.1`

### Erreur de Permissions sur les Fichiers
**Symptôme** : Erreurs d'écriture dans `var/cache` ou `var/log`

**Solutions** :
```bash
# Linux/Mac
chmod -R 777 var/
# Ou plus sécurisé :
chown -R www-data:www-data var/
chmod -R 775 var/
```

### Port 8000 Déjà Utilisé
**Symptôme** : `Address already in use`

**Solutions** :
```bash
# Utiliser un autre port
symfony server:start --port=8001
# Ou trouver et arrêter le processus
lsof -ti:8000 | xargs kill -9  # Linux/Mac
netstat -ano | findstr :8000   # Windows
```

### Erreurs de Migration
**Symptôme** : Les migrations échouent

**Solutions** :
```bash
# Réinitialiser complètement
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

-----

## 7\. 📝 Contribution et Licence

### Contribution
Les contributions sont les bienvenues ! Pour contribuer :
1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Pushez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

### Licence
Ce projet est développé dans le cadre du Lycée Fulbert pour la gestion des inscriptions au BTS SIO.

-----

## 8\. 📞 Support

Pour toute question ou problème :
- Consultez la documentation Symfony : https://symfony.com/doc/current/index.html
