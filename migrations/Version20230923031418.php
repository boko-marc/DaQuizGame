<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230923031418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_entity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz_entity (id INT NOT NULL, game_id INT NOT NULL, random VARCHAR(4) NOT NULL, true_actor INT NOT NULL, fake_actor INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E36CB431E48FD905 ON quiz_entity (game_id)');
        $this->addSql('ALTER TABLE quiz_entity ADD CONSTRAINT FK_E36CB431E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quiz_entity_id_seq CASCADE');
        $this->addSql('ALTER TABLE quiz_entity DROP CONSTRAINT FK_E36CB431E48FD905');
        $this->addSql('DROP TABLE quiz_entity');
    }
}
