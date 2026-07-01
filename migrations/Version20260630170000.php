<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260630170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add delivery details to orders';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande ADD prestation_date DATE DEFAULT NULL, ADD prestation_time TIME DEFAULT NULL, ADD delivery_address VARCHAR(255) DEFAULT NULL, ADD delivery_postal_code VARCHAR(10) DEFAULT NULL, ADD delivery_city VARCHAR(100) DEFAULT NULL, ADD delivery_details LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande DROP prestation_date, DROP prestation_time, DROP delivery_address, DROP delivery_postal_code, DROP delivery_city, DROP delivery_details');
    }
}
