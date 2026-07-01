<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260701130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add review refusal flag';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE avis ADD refused TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE avis DROP refused');
    }
}
