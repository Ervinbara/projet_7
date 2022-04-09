<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403164014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F68AB014612');
        $this->addSql('DROP INDEX IDX_A2161F68AB014612 ON user_client');
        $this->addSql('ALTER TABLE user_client CHANGE clients_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F6819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_A2161F6819EB6921 ON user_client (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F6819EB6921');
        $this->addSql('DROP INDEX IDX_A2161F6819EB6921 ON user_client');
        $this->addSql('ALTER TABLE user_client CHANGE client_id clients_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F68AB014612 FOREIGN KEY (clients_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_A2161F68AB014612 ON user_client (clients_id)');
    }
}
