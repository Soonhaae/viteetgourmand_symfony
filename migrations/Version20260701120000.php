<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260701120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add customer reviews for completed orders';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, user_id INT NOT NULL, rating INT NOT NULL, comment LONGTEXT NOT NULL, validated TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8F91ABF582EA2E54 (commande_id), INDEX IDX_8F91ABF5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF582EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE avis');
    }
}
