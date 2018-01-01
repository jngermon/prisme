<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180101205013 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE characterSkill_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE skill_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE characterSkill (id INT NOT NULL, character_id INT DEFAULT NULL, skill_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sync_uuid VARCHAR(36) DEFAULT NULL, external_id INT DEFAULT NULL, synced_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sync_status VARCHAR(10) DEFAULT NULL, sync_errors VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D525AFF01136BE75 ON characterSkill (character_id)');
        $this->addSql('CREATE INDEX IDX_D525AFF05585C142 ON characterSkill (skill_id)');
        $this->addSql('CREATE TABLE skill (id INT NOT NULL, larp_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, summary TEXT DEFAULT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sync_uuid VARCHAR(36) DEFAULT NULL, external_id INT DEFAULT NULL, synced_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sync_status VARCHAR(10) DEFAULT NULL, sync_errors VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E3DE47763FF2A01 ON skill (larp_id)');
        $this->addSql('ALTER TABLE characterSkill ADD CONSTRAINT FK_D525AFF01136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterSkill ADD CONSTRAINT FK_D525AFF05585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE47763FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE characterSkill DROP CONSTRAINT FK_D525AFF05585C142');
        $this->addSql('DROP SEQUENCE characterSkill_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE skill_id_seq CASCADE');
        $this->addSql('DROP TABLE characterSkill');
        $this->addSql('DROP TABLE skill');
    }
}
