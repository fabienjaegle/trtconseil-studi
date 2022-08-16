<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220816195417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE job_offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE application (id INT NOT NULL, candidate_id INT NOT NULL, job_offer_id INT NOT NULL, is_validated BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A45BDDC191BD8781 ON application (candidate_id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC13481D195 ON application (job_offer_id)');
        $this->addSql('CREATE TABLE job_offer (id INT NOT NULL, recruiter_id INT NOT NULL, title VARCHAR(150) NOT NULL, description VARCHAR(255) NOT NULL, workplace VARCHAR(100) NOT NULL, salary INT DEFAULT NULL, schedules VARCHAR(50) DEFAULT NULL, is_validated BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_288A3A4E156BE243 ON job_offer (recruiter_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, discr VARCHAR(255) NOT NULL, cv VARCHAR(255) DEFAULT NULL, is_validated BOOLEAN DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, company_address VARCHAR(255) DEFAULT NULL, company_zip_code VARCHAR(5) DEFAULT NULL, company_city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
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
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC191BD8781 FOREIGN KEY (candidate_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC13481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E156BE243 FOREIGN KEY (recruiter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE job_offer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE application DROP CONSTRAINT FK_A45BDDC191BD8781');
        $this->addSql('ALTER TABLE application DROP CONSTRAINT FK_A45BDDC13481D195');
        $this->addSql('ALTER TABLE job_offer DROP CONSTRAINT FK_288A3A4E156BE243');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
