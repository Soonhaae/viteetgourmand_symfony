<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260630162000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'allow customers to hide orders';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande ADD hidden_from_customer TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commande DROP hidden_from_customer');
    }
}
