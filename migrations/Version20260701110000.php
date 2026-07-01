<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260701110000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add management cancellation details to orders';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande ADD management_cancellation_contact VARCHAR(50) DEFAULT NULL, ADD management_cancellation_reason LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande DROP management_cancellation_contact, DROP management_cancellation_reason');
    }
}
