<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425220018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create relation OneToMany entre Menu et Image';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD menus_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F14041B84 FOREIGN KEY (menus_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F14041B84 ON image (menus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F14041B84');
        $this->addSql('DROP INDEX IDX_C53D045F14041B84 ON image');
        $this->addSql('ALTER TABLE image DROP menus_id');
    }
}
