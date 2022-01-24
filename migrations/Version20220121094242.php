<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121094242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE articles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE corpus_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dictionary_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE evaluation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE indicator_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE articles (id INT NOT NULL, auteur VARCHAR(255) DEFAULT NULL, doc_subtitle VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, titre_revue VARCHAR(255) DEFAULT NULL, periode INT DEFAULT NULL, numero_parent INT DEFAULT NULL, href_parent INT DEFAULT NULL, identifier INT DEFAULT NULL, resume_fr TEXT DEFAULT NULL, resume_en TEXT DEFAULT NULL, contenu TEXT DEFAULT NULL, biblio TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE corpus (id INT NOT NULL, has_user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, verou BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2A4391B9C09FBB4E ON corpus (has_user_id)');
        $this->addSql('CREATE TABLE dictionary (id INT NOT NULL, word VARCHAR(255) NOT NULL, translate VARCHAR(255) NOT NULL, elquals VARCHAR(255) DEFAULT NULL, translated VARCHAR(255) DEFAULT NULL, lang VARCHAR(255) DEFAULT NULL, lang_dest VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE evaluation (id INT NOT NULL, has_user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, regles VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1323A575C09FBB4E ON evaluation (has_user_id)');
        $this->addSql('CREATE TABLE indicator (id INT NOT NULL, has_user_id INT DEFAULT NULL, has_evaluation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, time_begin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, time_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D1349DB3C09FBB4E ON indicator (has_user_id)');
        $this->addSql('CREATE INDEX IDX_D1349DB3E7952A41 ON indicator (has_evaluation_id)');
        $this->addSql('CREATE TABLE resource (id INT NOT NULL, has_user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, resource_file VARCHAR(255) NOT NULL, verou BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC91F416C09FBB4E ON resource (has_user_id)');
        $this->addSql('CREATE TABLE resource_corpus (resource_id INT NOT NULL, corpus_id INT NOT NULL, PRIMARY KEY(resource_id, corpus_id))');
        $this->addSql('CREATE INDEX IDX_E2BF7B4389329D25 ON resource_corpus (resource_id)');
        $this->addSql('CREATE INDEX IDX_E2BF7B432B41ABF4 ON resource_corpus (corpus_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, can_access BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE corpus ADD CONSTRAINT FK_2A4391B9C09FBB4E FOREIGN KEY (has_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575C09FBB4E FOREIGN KEY (has_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE indicator ADD CONSTRAINT FK_D1349DB3C09FBB4E FOREIGN KEY (has_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE indicator ADD CONSTRAINT FK_D1349DB3E7952A41 FOREIGN KEY (has_evaluation_id) REFERENCES evaluation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416C09FBB4E FOREIGN KEY (has_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_corpus ADD CONSTRAINT FK_E2BF7B4389329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_corpus ADD CONSTRAINT FK_E2BF7B432B41ABF4 FOREIGN KEY (corpus_id) REFERENCES corpus (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE resource_corpus DROP CONSTRAINT FK_E2BF7B432B41ABF4');
        $this->addSql('ALTER TABLE indicator DROP CONSTRAINT FK_D1349DB3E7952A41');
        $this->addSql('ALTER TABLE resource_corpus DROP CONSTRAINT FK_E2BF7B4389329D25');
        $this->addSql('ALTER TABLE corpus DROP CONSTRAINT FK_2A4391B9C09FBB4E');
        $this->addSql('ALTER TABLE evaluation DROP CONSTRAINT FK_1323A575C09FBB4E');
        $this->addSql('ALTER TABLE indicator DROP CONSTRAINT FK_D1349DB3C09FBB4E');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F416C09FBB4E');
        $this->addSql('DROP SEQUENCE articles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE corpus_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dictionary_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE evaluation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE indicator_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE resource_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE corpus');
        $this->addSql('DROP TABLE dictionary');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE indicator');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_corpus');
        $this->addSql('DROP TABLE "user"');
    }
}
