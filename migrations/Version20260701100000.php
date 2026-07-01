<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260701100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add order status history';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE commande_status_history (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, status VARCHAR(255) NOT NULL, changed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5C6C4FD982EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_status_history ADD CONSTRAINT FK_5C6C4FD982EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO commande_status_history (commande_id, status, changed_at) SELECT id, status, date FROM commande');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE commande_status_history');
    }
}
