release: php bin/console doctrine:migrations:migrate --no-interaction && php bin/console doctrine:mongodb:schema:create && php bin/console cache:clear --env=prod && php bin/console cache:warmup --env=prod
web: heroku-php-apache2 public/
