<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260701090000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add delivery fee details to orders';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande ADD delivery_distance_km INT DEFAULT NULL, ADD delivery_price NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande DROP delivery_distance_km, DROP delivery_price');
    }
}
