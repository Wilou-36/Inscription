# 📚 Documentation d'installation - EasyBTS Lycée Fulbert (V1)



**Pour qui :** Ce document explique comment l'application fonctionne, comment l'installer et comment la maintenir.



## 1\. Ce que Fait l'Application et Pourquoi (Buts du Projet) 💡



### 1.1 Buts de la Version Actuelle (V1)



L'objectif est de remplacer le **dossier papier par un système en ligne**.



| Ce que le Site fait | Explication Simple | Terme Technique (Pour Mémoire) |


| **Garder l'avancement** | Si un étudiant quitte le site sans finir, il peut revenir et retrouver toutes ses informations pré-remplies. | **Gestion du Brouillon** |

| **Sécurité des Comptes** | Le système vérifie qui vous êtes (mot de passe fort) et donne des accès différents : Étudiant ou Administrateur. | **Authentification et Rôles** |

| **Suivi des Dossiers** | Le secrétariat peut changer l'état du dossier (Ex: "En Attente" devient "**Validé**" ou "**Refusé**"). | **Workflow Administratif** |

| **Export des Infos** | Le secrétariat peut télécharger toutes les données dans un fichier prêt à être utilisé dans d'autres logiciels (Ex: Excel ou le logiciel de gestion de l'école). | **Export CSV** |



### 1.2 Comment on Garantit la Sécurité et la Fiabilité 🔒



  * **Mots de Passe Secrets** : Les mots de passe sont transformés en codes illisibles (hashés) pour qu'ils ne puissent jamais être vus, même par l'administrateur du site.

  * **Trace des Décisions** : Chaque fois qu'un administrateur change l'état d'un dossier (de "En Attente" à "Validé"), le système enregistre automatiquement cette action dans un journal de bord.



-----



## 2\. Comment les Données sont Organisées (Le Plan de la Base de Données) 🗺️



### 2.1 Le Plan du Dossier (Modèle de Données)



Toutes les informations (adresse, documents, scolarité) sont rangées dans des **tiroirs** (les tables de la base de données). L'étudiant est le **dossier principal** qui relie tous les tiroirs entre eux.



  * **Le Dossier Central (`Etudiant`)** : C'est le point de départ. Il contient le nom, l'email et l'état (`statut`) du dossier. Il utilise des **liens uniques** pour pointer vers toutes les autres informations.

  * **Les Liens Uniques (Relation 1 pour 1)** : Un étudiant correspond exactement à **UNE** adresse, **UN** compte utilisateur, **UN** dossier de scolarité, et **UN** jeu de documents. Cela évite les erreurs et assure que le dossier est complet.

  * **Les Liens de Référence** : Le dossier de scolarité est relié à des listes prédéfinies, comme la liste des **formations disponibles** (`Scolarite`) ou la liste des **années scolaires**.



-----



## 3\. Comment les Parties de l'Application Communiquent (Le Code) ⚙️



L'application est construite avec **Symfony (un outil puissant en PHP)**, suivant le modèle **MVC (Modèle-Vue-Contrôleur)**.



| Nom du Code | Rôle dans l'Application | Ce qu'il fait en Détail |



| **Inscription** (Contrôleur) | **Gère tout le formulaire** que l'étudiant remplit. | Récupère toutes les données, les vérifie, gère l'envoi des documents (Ex: Carte Vitale), et stocke le brouillon. |

| **Administration** (Contrôleur) | **Le tableau de bord** du secrétariat. | Permet de filtrer les dossiers, de changer leur statut (Validation/Refus), et d'exporter les listes dans un fichier CSV. |

| **Sécurité** (Contrôleur) | **Gère l'accès** au site. | Vérifie les mots de passe et les rôles. C'est ici qu'on crée les comptes "Administrateur" et "Étudiant". |



### Le Service Email (Notification)



Quand un dossier est validé ou refusé, le système utilise un service d'envoi d'e-mails pour prévenir l'étudiant automatiquement.



-----



## 4\. Guide d'Installation (Pour Démarrer le Projet) 🛠️



Ce guide explique comment installer le projet **de A à Z** sur un nouvel ordinateur.



### 4.1. Étape 1 : Préparer l'Ordinateur



Vous avez besoin de ces outils de base pour faire fonctionner le site :



1.  **PHP 8.1 ou plus** (le langage de programmation).

2.  **Composer** (l'outil pour télécharger les pièces du site).

3.  **Git** (l'outil pour copier le projet).

4.  **MySQL** ou **MariaDB** (le logiciel pour gérer la base de données).



### 4.2. Étape 2 : Installer le Projet



1.  **Copier le Code** : Ouvrez le terminal (ligne de commande) et copiez le projet :

    ```bash

    git clone https://github.com/Wilou-36/Inscription easybts

    cd easybts

    ```

2.  **Installer les Pièces** : Téléchargez toutes les dépendances :

    ```bash

    composer install

    ```

3.  **Lier la Base de Données** : Dans le fichier de configuration **`.env.local`**, entrez les identifiants pour que le site puisse parler à votre base de données.

4.  **Créer les Tiroirs** : Le système utilise **Doctrine** pour créer toutes les tables (les tiroirs) automatiquement :

    ```bash

    php bin/console doctrine:database:create

    php bin/console doctrine:migrations:migrate --no-interaction

    ```



### 4.3. Étape 3 : Lancer le Site



1.  **Démarrer le Serveur** :

    ```bash

    symfony server:start

    ```

2.  **Créer un compte** : Accédez à l'adresse **`https://127.0.0.1:8000/`** dans votre navigateur et créez le premier compte d'une longue série.



Le site est maintenant opérationnel. Vous pouvez vous connecter pour accéder à votre dossier d'inscription ou au tableau de bord de l'administration.
