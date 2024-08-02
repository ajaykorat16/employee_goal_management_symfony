<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240802091621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback CHANGE reporter_id reporter_id INT NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458E1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_D2294458E1CFE6F5 ON feedback (reporter_id)');
        $this->addSql('ALTER TABLE goals ADD reporter_id INT NOT NULL');
        $this->addSql('ALTER TABLE goals ADD CONSTRAINT FK_C7241E2FE1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_C7241E2FE1CFE6F5 ON goals (reporter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458E1CFE6F5');
        $this->addSql('DROP INDEX IDX_D2294458E1CFE6F5 ON feedback');
        $this->addSql('ALTER TABLE feedback CHANGE reporter_id reporter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goals DROP FOREIGN KEY FK_C7241E2FE1CFE6F5');
        $this->addSql('DROP INDEX IDX_C7241E2FE1CFE6F5 ON goals');
        $this->addSql('ALTER TABLE goals DROP reporter_id');
    }
}
