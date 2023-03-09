<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301150806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_event (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, events_id INT DEFAULT NULL, INDEX IDX_8D262183A76ED395 (user_id), INDEX IDX_8D2621839D6A1065 (events_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_event ADD CONSTRAINT FK_8D262183A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE favorite_event ADD CONSTRAINT FK_8D2621839D6A1065 FOREIGN KEY (events_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_event DROP FOREIGN KEY FK_8D262183A76ED395');
        $this->addSql('ALTER TABLE favorite_event DROP FOREIGN KEY FK_8D2621839D6A1065');
        $this->addSql('DROP TABLE favorite_event');
    }
}
