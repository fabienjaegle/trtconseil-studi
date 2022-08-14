<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220814074022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE consultant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE consultant (id INT NOT NULL, userlink_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_441282A131C5FECF ON consultant (userlink_id)');
        $this->addSql('ALTER TABLE consultant ADD CONSTRAINT FK_441282A131C5FECF FOREIGN KEY (userlink_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE consultant_id_seq CASCADE');
        $this->addSql('ALTER TABLE consultant DROP CONSTRAINT FK_441282A131C5FECF');
        $this->addSql('DROP TABLE consultant');
        $this->addSql('DROP INDEX "primary"');
    }
}
