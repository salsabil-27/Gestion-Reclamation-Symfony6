<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308104816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE register_evenement (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, user_id INT DEFAULT NULL, registration_date DATE NOT NULL, INDEX IDX_83D47DA9FD02F13 (evenement_id), INDEX IDX_83D47DA9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE register_evenement ADD CONSTRAINT FK_83D47DA9FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE register_evenement ADD CONSTRAINT FK_83D47DA9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE evenement ADD register_evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EABC7B22A FOREIGN KEY (register_evenement_id) REFERENCES register_evenement (id)');
        $this->addSql('CREATE INDEX IDX_B26681EABC7B22A ON evenement (register_evenement_id)');
        $this->addSql('ALTER TABLE user ADD register_evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ABC7B22A FOREIGN KEY (register_evenement_id) REFERENCES register_evenement (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649ABC7B22A ON user (register_evenement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EABC7B22A');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649ABC7B22A');
        $this->addSql('ALTER TABLE register_evenement DROP FOREIGN KEY FK_83D47DA9FD02F13');
        $this->addSql('ALTER TABLE register_evenement DROP FOREIGN KEY FK_83D47DA9A76ED395');
        $this->addSql('DROP TABLE register_evenement');
        $this->addSql('DROP INDEX IDX_B26681EABC7B22A ON evenement');
        $this->addSql('ALTER TABLE evenement DROP register_evenement_id');
        $this->addSql('DROP INDEX IDX_8D93D649ABC7B22A ON `user`');
        $this->addSql('ALTER TABLE `user` DROP register_evenement_id');
    }
}
