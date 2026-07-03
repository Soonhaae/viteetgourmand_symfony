# ViteEtGourmand — Symfony

Ce projet est une application web développée avec **Symfony 8** pour un traiteur, permettant la gestion des menus, des plats, des commandes et des utilisateurs.

## Installation avec Docker

### Prérequis

- Docker
- Docker Compose

### 1. Cloner le dépôt

```bash
git clone https://github.com/Soonhaae/viteetgourmand_symfony.git
cd viteetgourmand_symfony
```

### 2. Construire l'image et démarrer les services

Au premier lancement, ou après une modification du `Dockerfile` :

```bash
docker compose up --build -d
```

### 3. Installer les dépendances PHP

```bash
docker compose exec php composer install
```

### 4. Créer la base et exécuter les migrations

(C'est ce que fait Heroku avec le `Procfile` mais en retirant --env=prod car on est en dev, et en ne faisant pas de cache:clear ni cache:warmup.)

```bash
docker compose exec -T php php bin/console doctrine:database:create --if-not-exists --no-interaction
docker compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction
```

### 5. Créer le schéma MongoDB

Pour initialiser les collections utilisées par Doctrine ODM :

```bash
docker compose exec -T php php bin/console doctrine:mongodb:schema:create --no-interaction
```

Si vous devez repartir de zéro côté MongoDB :

```bash
docker compose exec -T php php bin/console doctrine:mongodb:schema:drop --force --no-interaction
docker compose exec -T php php bin/console doctrine:mongodb:schema:create --no-interaction
```

### 6. Importer les données initiales

Si vous voulez charger le fichier SQL fourni à la racine du projet :

(Le fichier `viteetgourmand_symfony.sql` contient des `INSERT`. Si les tables sont deja remplies, il faut les vider avant l'import.)

**Bash**

```bash
docker compose exec -T database sh -lc 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' < viteetgourmand_symfony.sql
```

Attention : pas d'espace entre `-p` et le mot de passe.  

**PowerShell**

(quand `<` ne fonctionne pas)

```powershell
Get-Content viteetgourmand_symfony.sql | docker compose exec -T database sh -lc 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"'
```

### 7. Réinitialiser les volumes si besoin

Cette commande supprime les conteneurs et les volumes MySQL et MongoDB du projet :

```bash
docker compose down -v
```

Puis relancer :

```bash
docker compose up -d
```

## Configuration locale des bases

Symfony lit la configuration des bases via les variables d'environnement suivantes :

- `DATABASE_URL` pour MySQL/Doctrine ORM
- `MONGODB_URL` pour MongoDB/Doctrine ODM
- `MONGODB_DB` pour le nom de la base MongoDB

Les valeurs de dev utilisées par Docker sont les suivantes :

```dotenv
DATABASE_URL="mysql://app:app_dev_password@database:3306/app?serverVersion=8.0&charset=utf8mb4"
MONGODB_URL="mongodb://mongo_root:mongo_root_password@mongodb:27017/?authSource=admin"
MONGODB_DB="app_mongo"
MYSQL_DATABASE=app
MYSQL_USER=app
MYSQL_PASSWORD=app_dev_password
MYSQL_ROOT_PASSWORD=root_dev_password
MONGODB_USERNAME=mongo_root
MONGODB_PASSWORD=mongo_root_password
```

Si vous créez un fichier `.env.local`, ces valeurs peuvent y être redéfinies pour pointer vers une autre instance locale.

### Connexion DBeaver

Pour consulter la base MySQL locale avec DBeaver :

- **Host** : `localhost`
- **Port** : `3306`
- **Database** : `app`
- **User** : `app`
- **Password** : `app_dev_password`

Si DBeaver affiche l'erreur **Public Key Retrieval is not allowed** :

- Ouvrir la connexion puis aller dans **Driver properties**
- Mettre **allowPublicKeyRetrieval** a `true`
- Éventuellement, mettre **useSSL** a `false` (ou **sslMode** a `DISABLED` selon le driver)
- Enregistrer puis relancer **Test Connection**

Compte administrateur MySQL optionnel :

- **User** : `root`
- **Password** : `root_dev_password`

### Connexion MongoDB Compass

Pour consulter la base MongoDB locale avec MongoDB Compass :

- **Host** : `localhost`
- **Port** : `27017`
- **Authentication** : `Username / Password`
- **Username** : `mongo_root`
- **Password** : `mongo_root_password`
- **Authentication Database** : `admin`

URI de connexion : `mongodb://mongo_root:mongo_root_password@localhost:27017/?authSource=admin`

## Accès

- Application Symfony : http://localhost:8080

