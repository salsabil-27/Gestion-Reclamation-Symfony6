<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301151956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_event DROP FOREIGN KEY FK_8D2621839D6A1065');
        $this->addSql('DROP INDEX IDX_8D2621839D6A1065 ON favorite_event');
        $this->addSql('ALTER TABLE favorite_event CHANGE events_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorite_event ADD CONSTRAINT FK_8D26218371F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_8D26218371F7E88B ON favorite_event (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_event DROP FOREIGN KEY FK_8D26218371F7E88B');
        $this->addSql('DROP INDEX IDX_8D26218371F7E88B ON favorite_event');
        $this->addSql('ALTER TABLE favorite_event CHANGE event_id events_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorite_event ADD CONSTRAINT FK_8D2621839D6A1065 FOREIGN KEY (events_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_8D2621839D6A1065 ON favorite_event (events_id)');
    }
}
