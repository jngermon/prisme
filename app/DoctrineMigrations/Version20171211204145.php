<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171211204145 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE charactergroup DROP CONSTRAINT fk_e6d80f42fe54d947');
        $this->addSql('DROP SEQUENCE group_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE larp_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE larp_group (id INT NOT NULL, larp_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_376E565363FF2A01 ON larp_group (larp_id)');
        $this->addSql('ALTER TABLE larp_group ADD CONSTRAINT FK_376E565363FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('ALTER TABLE charactergroup ADD CONSTRAINT FK_E6D80F42FE54D947 FOREIGN KEY (group_id) REFERENCES larp_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE characterGroup DROP CONSTRAINT FK_E6D80F42FE54D947');
        $this->addSql('DROP SEQUENCE larp_group_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, larp_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6dc044c563ff2a01 ON "group" (larp_id)');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT fk_6dc044c563ff2a01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE larp_group');
        $this->addSql('ALTER TABLE characterGroup ADD CONSTRAINT fk_e6d80f42fe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
