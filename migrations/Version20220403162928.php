<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403162928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_client_client');
        $this->addSql('ALTER TABLE user_client ADD clients_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F68AB014612 FOREIGN KEY (clients_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_A2161F68AB014612 ON user_client (clients_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_client_client (user_client_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_7C6CE2B619EB6921 (client_id), INDEX IDX_7C6CE2B6190BE4C5 (user_client_id), PRIMARY KEY(user_client_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_client_client ADD CONSTRAINT FK_7C6CE2B619EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_client_client ADD CONSTRAINT FK_7C6CE2B6190BE4C5 FOREIGN KEY (user_client_id) REFERENCES user_client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F68AB014612');
        $this->addSql('DROP INDEX IDX_A2161F68AB014612 ON user_client');
        $this->addSql('ALTER TABLE user_client DROP clients_id');
    }
}
