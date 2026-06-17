FROM php:8.4-apache
# comme php:8.4-fpm a déjà un CMD par défaut qui lance PHP-FPM, pas besoin de le redéfinir en indiquant "CMD ["php-fpm"]"

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite
# "a2enmod rewrite" = pour activer le module Apache "rewrite" qui permet de réécrire les URLs (utile pour Symfony)

# le RUN = une commande Linux en 3 étapes "chaînées" :
# apt-get update → pour mettre à jour la liste des paquets disponibles
# apt-get install -y git unzip libzip-dev → pour installer des outils système dont PHP a besoin
# docker-php-ext-install pdo pdo_mysql zip → pour installer des extensions PHP spécifiques (pdo et pdo_mysql pour parler à MySQL + zip pour manipuler des archives)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY apache.conf /etc/apache2/sites-available/000-default.conf
# pour copier la config Apache que j'ai créée dans le fichier apache.conf et remplacer la config par défaut d'Apache (qui renvoie sur /var/www/html) par ma config perso (qui renvoie sur /public)




WORKDIR /var/www/html
# j'aurais aussi pu mettre WORKDIR /app mais apparemment pour les projets PHP/Symfony "/var/www/html" est plus standard



# j'ai retiré : COPY composer.json composer.lock ./
# pour copier les fichiers composer.json et composer.lock dans le conteneur (dans le dossier de travail /var/www/html)


COPY . .
# sert à copier le projet symfony complet dans le conteneur (dans le dossier de travail /var/www/html)



# pour éviter d'exécuter le conteneur en tant que root (ce qui est une mauvaise pratique), je crée un utilisateur "symfony" et je lui donne les droits sur le dossier de travail
RUN useradd -m symfony \
    # ça crée un utilisateur "symfony" (qui correspondra à un identifiant système, un ID, mais que je ne connais pas mais docker fonctionne avec des ID, pas des noms)
    # et puis ça va aussi créerr (-m) le home directory s'il est inexistant (/home/symfony) car chauq eutilisateur a un "espace personnel" (avec fichiers config, clés SSH, cache, historique shell, etc.).
    && mkdir -p /var/www/html/var \
    && chown -R symfony:symfony /var/www/html

# mkdir -p /var/www/html/var → ça crée le dossier var/
# chown -R symfony:symfony /var/www/html → ça donne les droits (la propriété des fichiers) sur tout le projet à l'utilisateur symfony


USER symfony
# donc php + composer tourent avec cet utilisateur "symfony" et non plus avec root (=bonne pratique de sécurité)

RUN composer install
# pour installer les dépendances PHP du projet (Symfony) en utilisant Composer
