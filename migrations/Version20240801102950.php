<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801102950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goals ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE goals ADD CONSTRAINT FK_C7241E2FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_C7241E2FA76ED395 ON goals (user_id)');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE goals DROP FOREIGN KEY FK_C7241E2FA76ED395');
        // $this->addSql('DROP INDEX IDX_C7241E2FA76ED395 ON goals');
        $this->addSql('ALTER TABLE goals DROP user_id');
        // $this->addSql('ALTER TABLE `user` CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
