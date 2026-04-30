<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425132023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'class Menu new properties minPersons, price, conditions';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu ADD min_persons INT NOT NULL, ADD price NUMERIC(10, 2) NOT NULL, ADD conditions VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu DROP min_persons, DROP price, DROP conditions');
    }
}
