# ViteEtGourmand — Symfony

Ce projet est une application web développée avec **Symfony 8** pour un traiteur, permettant la gestion des menus, des plats, des commandes et des utilisateurs.

> Les instructions d'installation ci-dessous sont destinées à un environnement **Windows avec XAMPP**.

## Prérequis

- **[XAMPP](https://www.apachefriends.org/)** (fournit PHP >= 8.4 et MySQL)
- **[Composer](https://getcomposer.org/download/)**
- **[Git](https://git-scm.com/)**

### Activer les extensions PHP requises

Ouvrir le fichier `php.ini` de XAMPP (par défaut `C:\xampp\php\php.ini`) et décommenter les lignes suivantes (supprimer le `;` en début de ligne) :

```ini
extension=pdo_mysql
extension=intl
extension=zip
extension=curl
```

Redémarrer Apache depuis le panneau de contrôle XAMPP après modification.

## Installation

Les commandes ci-dessous sont à exécuter dans un terminal (**PowerShell** ou **invite de commandes**), depuis le dossier du projet.

### 1. Cloner le dépôt

```powershell
git clone https://github.com/Soonhaae/viteetgourmand_symfony.git
cd viteetgourmand_symfony
```

### 2. Installer les dépendances PHP

```powershell
composer install
```

### 3. Configurer la base de données

Créer un fichier `.env.local` à la racine (ce fichier est ignoré par git) et y renseigner l'URL de connexion à la base de données :

```dotenv
DATABASE_URL="mysql://root:@127.0.0.1:3306/viteetgourmand?serverVersion=8.0&charset=utf8mb4"
```

> Avec XAMPP, le compte `root` sans mot de passe est standard. Si votre configuration est différente, adaptez les identifiants.

### 4. Créer la base de données et jouer les migrations

Démarrer **MySQL** depuis le panneau de contrôle XAMPP, puis exécuter :

```powershell
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. (Optionnel) Insérer des données initiales

Un fichier `viteetgourmand_symfony.sql` est disponible à la racine pour pré-remplir la base. Depuis PowerShell :

```powershell
Get-Content viteetgourmand_symfony.sql | mysql -u root viteetgourmand
```

### 6. Lancer le serveur de développement

```powershell
php -S localhost:8000 -t public/
```

L'application est accessible à l'adresse : [http://localhost:8000](http://localhost:8000)

## Structure du projet

| Dossier / Fichier | Rôle |
|---|---|
| `src/Controller/` | Contrôleurs (index, menus, compte, inscription, sécurité, admin) |
| `src/Entity/` | Entités Doctrine : `Plat`, `Menu`, `Commande`, `User`, `Allergene`, `Regime`, `Image` |
| `src/Form/` | Formulaires Symfony |
| `src/Repository/` | Repositories Doctrine |
| `templates/` | Templates Twig |
| `migrations/` | Migrations Doctrine |
| `public/` | Point d'entrée web (`index.php`) |
| `assets/` | Assets front-end (CSS, JS) |

## Déploiement sur Heroku

1. Sur le tableau de bord Heroku :
   - Lier le dépôt GitHub https://github.com/Soonhaae/viteetgourmand_symfony (onglet *Deploy*)
   - Ajouter la variable `APP_ENV=prod` (onglet *Settings > Config Vars*)
   - Ajouter le add-on **JawsDB MySQL** (onglet *Resources*)
   - Une fois JawsDB provisionné, copier la valeur de `JAWSDB_URL` et créer une variable `DATABASE_URL` avec la même valeur

2. Déployer manuellement depuis la branche `main`.  
   Le `Procfile` exécute automatiquement les migrations Doctrine, ce qui crée les tables en base.

3. Insérer les données initiales en base via la commande `mysql` :

```powershell
Get-Content INSERT.sql | mysql -h <host> -P <port> -u <username> -p<password> <database>
```

> Attention : pas d'espace entre `-p` et le mot de passe.  
> Les informations de connexion sont disponibles sur la page du add-on JawsDB (onglet *Resources*, cliquer sur *JawsDB MySQL*), ou en décomposant la `DATABASE_URL` : `mysql://username:password@host:port/database`.
