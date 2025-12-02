# 🎓 EasyBTS - Lycée Fulbert (V1) : Documentation Technique

## Application d'Inscription en BTS SIO (SLAM / SISR)

Ce projet vise à **numériser** le processus de candidature au BTS SIO, offrant une plateforme sécurisée pour les étudiants et un outil de gestion efficace pour le secrétariat.

-----

## 1\. 🚀 Contexte Technique et Stack

L'application est développée sous l'architecture **LAMP** (Linux, Apache/Nginx, MySQL/MariaDB, PHP) et utilise le framework Symfony pour structurer le code.

### 1.1. Technologies Principales

| Technologie | Version | Rôle dans le Projet |
| :--- | :--- | :--- |
| **PHP** | 8.1+ | Langage de programmation principal. |
| **Symfony** | 6.x / 7.x | Framework backend (structure, routing, services). |
| **Doctrine ORM** | -- | Gestion de la persistance des données (mapping Objet-Relationnel). |
| **MySQL / MariaDB** | -- | Système de Gestion de Base de Données (SGBD). |
| **Twig** | -- | Moteur de template pour l'affichage côté client (Vue). |
| **Bootstrap** | 5.x | Framework CSS pour le design et le responsive. |

### 1.2. Organisation du Code (Architecture MVC)

Le code suit le modèle **MVC (Modèle-Vue-Contrôleur)**, ce qui garantit une séparation claire des responsabilités :

  * **Contrôleur (`src/Controller`)** : Gère la logique de la requête HTTP, appelle le Modèle, et prépare la Vue (ex: `InscriptionController.php`).
  * **Modèle (`src/Entity`, `src/Repository`)** : Représente les données (Entités Doctrine) et gère l'interaction avec la base de données.
  * **Vue (`templates/`)** : Affiche les données grâce au moteur Twig (le HTML final).

-----

## 2\. 🛡️ Modèle de Données et Sécurité

Le schéma de la base de données est structuré pour maximiser la cohérence et minimiser la redondance.

### 2.1. Structure du Dossier

L'entité `Etudiant` est le cœur du système. Toutes les autres informations sont liées à celle-ci via des relations un-à-un (`OneToOne`), garantissant que chaque dossier est complet et unique.

  * **`Etudiant`** : Détails du candidat (`nom`, `prenom`, `statut`, `date_naissance`).
  * **`Utilisateur`** : Gestion de la connexion (`identifiant`, `mot_de_passe`, `role`). Relation un-à-un avec l'étudiant.
  * **`DossierScolarite`** : Parcours académique antérieur (`regime_sco`, `specialite`).
  * **`DocEtudiant`** : Liens vers les documents dématérialisés (`carte_vitale`, `diplome`, etc.).

### 2.2. Gestion des Rôles

Deux rôles principaux sont définis :

  * `ROLE_USER` : Accès aux formulaires d'inscription et à la page de suivi de son propre dossier.
  * `ROLE_ADMIN` : Accès au tableau de bord d'administration, filtration, validation/refus, et export des données.

La vérification des accès est gérée par le composant **Symfony Security**.

-----

## 3\. ⚙️ Guide d'Installation (Pas-à-Pas Détaillé)

Ce guide permet d'initialiser l'environnement de développement complet et de le peupler avec les données de test.

### 3.1. Étape 1 : Préparation du Projet

Assurez-vous d'avoir les outils prérequis (PHP 8.1+, Composer, Git) installés et configurés.

| Commande | Description |
| :--- | :--- |
| `git clone https://github.com/Wilou-36/Inscription easybts` | Télécharge le code source du projet. |
| `cd easybts` | Se place dans le répertoire de travail. |
| `composer install` | Installe toutes les dépendances PHP et initialise les fichiers d'autoload. |

### 3.2. Étape 2 : Configuration et Schéma de la Base de Données

Nous utilisons l'outil Doctrine pour gérer la base de données.

1.  **Configuration de la Connexion** : Ouvrez le fichier **`.env.local`** et définissez la chaîne de connexion `DATABASE_URL` pour pointer vers votre instance MySQL locale.

    *Exemple :* `DATABASE_URL="mysql://root:motdepasse@127.0.0.1:3306/easybts_db"`

2.  **Création de la Base de Données** : Cette commande utilise la configuration du `.env` pour créer la base de données vide.

<!-- end list -->

```bash
php bin/console doctrine:database:create
```

3.  **Création des Tables (Schéma)** : Exécutez les migrations pour appliquer la structure des tables (schéma) définie par les entités Doctrine.

<!-- end list -->

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

### 3.3. Étape 3 : Insertion des Données de Test (Fixtures) 📝

Pour le test, nous injectons un jeu de **15 dossiers diversifiés** incluant différents statuts (`valide`, `refusé`, `en_attente`).

1.  **Vérification du Fichier SQL** : Confirmez que le fichier **`diversity_fixtures_final.sql`** (contenant toutes les commandes `TRUNCATE` et `INSERT`) se trouve dans le dossier **`sql/`**.

2.  **Exécution du Script** : La commande ci-dessous lit le fichier et exécute son contenu via l'outil Doctrine.

<!-- end list -->

```bash
# ATTENTION : Cette commande VIDE d'abord les tables (TRUNCATE TABLE)
php bin/console doctrine:query:sql "$(cat sql/diversity_fixtures_final.sql)"
```

**Résultat :** La base de données est maintenant remplie, et les comptes de test sont accessibles.

| Rôle | Identifiant | Mot de Passe | Statut dans la BDD |
| :--- | :--- | :--- | :--- |
| **Administrateur** | `admin@fulbert.fr` | `password` | `ROLE_ADMIN` |
| **Étudiant (Validé)** | `samir.elhassani@test.com` | `password` | `ROLE_USER` (`statut: valide`) |
| **Étudiant (Refusé)** | `marc.legrand@test.com` | `password` | `ROLE_USER` (`statut: refusé`) |

### 3.4. Étape 4 : Démarrage et Vérification

Lancez l'application pour commencer le développement ou les tests.

1.  **Démarrage du Serveur** :

<!-- end list -->

```bash
symfony server:start
```

2.  **Accès à l'Application** :

<!-- end list -->

  * Ouvrez votre navigateur à l'adresse : **`https://127.0.0.1:8000/`**

### 3.5. Guide Post-Installation

Une fois le site lancé, effectuez ces vérifications rapides :

1.  **Vérification Administrateur** : Connectez-vous avec `admin@fulbert.fr / password`. Vous devriez voir les **15 dossiers** dans le tableau de bord d'administration.
2.  **Vérification Étudiant** : Déconnectez-vous, puis connectez-vous avec `samir.elhassani@test.com / password`. Vous devriez voir son dossier avec le statut **Validé**.
3.  **Vérification du Schéma** : Vous pouvez vérifier la structure des tables directement dans votre outil SGBD (ex: phpMyAdmin, DBeaver).
