# 📚 EasyBTS - Lycée Fulbert (V1) : Documentation Technique 

## Guide d'Installation Point-Par-Point 

Ce document est le guide de référence complet pour installer, configurer et lancer l'application EasyBTS, en fournissant toutes les étapes d'infrastructure et de code.

-----

## 1\. 🔍 Contexte du Projet et Stack Technologique

### 1.1. Objectifs Fonctionnels

L'application EasyBTS est une plateforme web visant à dématérialiser le processus d'admission au BTS SIO.

  * **Sécurité et Suivi** : Assurer l'intégrité des données et permettre le suivi précis du statut de chaque dossier (`en attente`, `validé`, `refusé`).
  * **Accessibilité** : Offrir une interface conforme aux normes de l'État (via le DSFR) pour les candidats et le personnel administratif.
  * **Efficacité** : Fournir des outils d'administration (filtration, export CSV) pour rationaliser le travail du secrétariat.

### 1.2. Architecture Logicielle et Outils

Le projet est basé sur une stack **Symfony** moderne, garantissant la robustesse et la maintenabilité.

| Composant | Rôle Précis | Justification Technique |
| :--- | :--- | :--- |
| **Backend** | **Symfony (PHP 8.1+)** | Fournit le routing, le conteneur de services et la structure MVC. |
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

Ce guide est un chemin d'exécution séquentiel, assumant que vous partez d'une machine de développement propre (hôte) avec les prérequis installés (Git, PHP, Composer, npm).

### 3.1. Étape 1 : Mise en Place de l'Infrastructure du Serveur BDD

Le serveur de base de données doit être créé et configuré pour être accessible depuis la machine hôte.

#### 3.1.1. Choix et Initialisation du Serveur SGBD

  * **Si VM (Virtual Machine)** : Créez une VM (ex: Ubuntu), installez le paquet `mysql-server` et assurez-vous que le **port 3306 est ouvert** et accessible depuis l'IP de votre machine hôte.
  * **Si Docker (Recommandé en Dev)** : Lancez un conteneur MariaDB/MySQL.

#### 3.1.2. Création de l'Utilisateur de l'Application

Connectez-vous à la console MySQL de votre serveur BDD (VM/Docker) pour créer l'utilisateur qui sera utilisé par Symfony.

| Commande MySQL | Objectif Précis |
| :--- | :--- |
| `CREATE USER 'app'@'%' IDENTIFIED BY 'password_fort';` | Crée l'utilisateur **`app`** pour la connexion à distance. (`%` est l'hôte, utilisez l'IP de votre machine hôte si vous voulez restreindre l'accès). |
| `GRANT ALL PRIVILEGES ON *.* TO 'app'@'%';` | Donne tous les droits nécessaires à Doctrine pour créer la base de données et manipuler toutes les tables. |
| `FLUSH PRIVILEGES;` | Active les nouvelles permissions immédiatement. |

### 3.2. Étape 2 : Récupération du Code et Dépendances

Ces commandes sont exécutées sur votre **machine hôte** (poste de développement).

| Action | Commande | Explication Détaillée |
| :--- | :--- | :--- |
| **Clonage du Dépôt** | `git clone https://github.com/Wilou-36/Inscription easybts` | **Clône le dépôt** du projet dans le dossier `easybts`. |
| **Accès au Projet** | `cd easybts` | Se positionne dans le répertoire racine du projet. Toutes les commandes suivantes sont exécutées d'ici. |
| **Dépendances PHP** | `composer install` | Lit le fichier `composer.lock` et télécharge précisément toutes les dépendances Backend (Symfony, Doctrine). |
| **Dépendances Frontend** | `npm install @gouvfr/dsfr` | Installe le paquet du **Design System de l'État Français (DSFR)** pour les composants visuels. |

### 3.3. Étape 3 : Configuration du Lien BDD et Schéma

Cette étape connecte le code au serveur BDD distant et crée la structure de la base de données.

1.  **Configuration du Fichier `.env.local`** :

      * Ouvrez le fichier **`.env.local`**.
      * Définissez la variable **`DATABASE_URL`** en utilisant l'adresse IP de votre serveur SGBD (VM ou Docker) et les identifiants créés à l'Étape 1.

    <!-- end list -->

    ```env
    # Format : mysql://USER:PASSWORD@HOST_IP:PORT/DB_NAME
    DATABASE_URL="mysql://app:password_fort@<IP_DE_VOTRE_VM>:3306/easybts_db"
    ```

2.  **Création de la Base de Données Logique** :

      * Se connecte au serveur SGBD et envoie l'instruction `CREATE DATABASE easybts_db;`.

<!-- end list -->

```bash
php bin/console doctrine:database:create
```

3.  **Création des Tables (Schéma)** :
      * Exécute toutes les migrations Doctrine pour créer toutes les tables et colonnes basées sur les Entités du projet.

<!-- end list -->

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

### 3.4. Étape 4 : Insertion des Données de Test (Fixtures) 📝

Pour le développement, nous injectons 15 dossiers de test complets, incluant tous les scénarios de statut possibles.

1.  **Vérification du Fichier Source** : Confirmez que le script **`diversity_fixtures_final.sql`** est présent dans le dossier **`sql/`**.

2.  **Exécution du Script SQL** :

      * Cette commande lit le contenu du fichier et l'envoie à la BDD. Elle est conçue pour **vider les tables existantes** (`TRUNCATE TABLE`) avant d'insérer les nouvelles données.

<!-- end list -->

```bash
# COMMANDE EXÉCUTANT LE FICHIER DE FIXTURES
php bin/console doctrine:query:sql "$(cat sql/diversity_fixtures_final.sql)"
```

**Comptes de Test Pré-chargés :**

| Rôle | Identifiant | Mot de Passe | Statut dans la BDD |
| :--- | :--- | :--- | :--- |
| **Administrateur** | `admin@fulbert.fr` | `password` | `ROLE_ADMIN` |
| **Étudiant (Validé)** | `samir.elhassani@test.com` | `password` | `statut: valide` |

### 3.5. Étape 5 : Démarrage du Serveur et Validation Finale

L'application est maintenant entièrement configurée et prête à être exécutée.

1.  **Lancement du Serveur** :

<!-- end list -->

```bash
symfony server:start
```

2.  **Accès à l'Application** :
      * Ouvrez votre navigateur à l'adresse : **`https://127.0.0.1:8000/`**

**Validation Finale :**

  * **Test Admin** : Connectez-vous avec `admin@fulbert.fr` et vérifiez que le tableau de bord affiche les **15 dossiers** chargés.
  * **Test Utilisateur** : Connectez-vous avec `samir.elhassani@test.com` pour vérifier l'affichage du statut `validé`.
