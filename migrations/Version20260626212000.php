<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260626212000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add stock available to menu';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE menu ADD stock_available INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE menu DROP stock_available');
    }
}
