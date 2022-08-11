<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220811143549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE advertisement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE applicant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recruiter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE advertisement (id INT NOT NULL, recruiter_id INT NOT NULL, consultant_id INT DEFAULT NULL, title VARCHAR(200) NOT NULL, position VARCHAR(100) NOT NULL, city VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, isvalidated BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C95F6AEE156BE243 ON advertisement (recruiter_id)');
        $this->addSql('CREATE INDEX IDX_C95F6AEE44F779A2 ON advertisement (consultant_id)');
        $this->addSql('CREATE TABLE applicant (id INT NOT NULL, userlink_id INT NOT NULL, consultant_id INT DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAAD101931C5FECF ON applicant (userlink_id)');
        $this->addSql('CREATE INDEX IDX_CAAD101944F779A2 ON applicant (consultant_id)');
        $this->addSql('CREATE TABLE application (id INT NOT NULL, advertisement_id INT NOT NULL, applicant_id INT NOT NULL, isapproved BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A45BDDC1A1FBF71B ON application (advertisement_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC197139001 ON application (applicant_id)');
        $this->addSql('CREATE TABLE recruiter (id INT NOT NULL, userlink_id INT NOT NULL, companyname VARCHAR(255) NOT NULL, companyaddress VARCHAR(255) NOT NULL, companyzipcode VARCHAR(5) NOT NULL, companycity VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DE8633D831C5FECF ON recruiter (userlink_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE advertisement ADD CONSTRAINT FK_C95F6AEE156BE243 FOREIGN KEY (recruiter_id) REFERENCES recruiter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE advertisement ADD CONSTRAINT FK_C95F6AEE44F779A2 FOREIGN KEY (consultant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE applicant ADD CONSTRAINT FK_CAAD101931C5FECF FOREIGN KEY (userlink_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE applicant ADD CONSTRAINT FK_CAAD101944F779A2 FOREIGN KEY (consultant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1A1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC197139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recruiter ADD CONSTRAINT FK_DE8633D831C5FECF FOREIGN KEY (userlink_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE advertisement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE applicant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recruiter_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE advertisement DROP CONSTRAINT FK_C95F6AEE156BE243');
        $this->addSql('ALTER TABLE advertisement DROP CONSTRAINT FK_C95F6AEE44F779A2');
        $this->addSql('ALTER TABLE applicant DROP CONSTRAINT FK_CAAD101931C5FECF');
        $this->addSql('ALTER TABLE applicant DROP CONSTRAINT FK_CAAD101944F779A2');
        $this->addSql('ALTER TABLE application DROP CONSTRAINT FK_A45BDDC1A1FBF71B');
        $this->addSql('ALTER TABLE application DROP CONSTRAINT FK_A45BDDC197139001');
        $this->addSql('ALTER TABLE recruiter DROP CONSTRAINT FK_DE8633D831C5FECF');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE applicant');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE recruiter');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
