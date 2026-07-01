<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260701133000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add public publication fields for customer reviews';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE avis ADD published TINYINT(1) DEFAULT 0 NOT NULL, ADD public_excerpt VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE avis DROP published, DROP public_excerpt');
    }
}
