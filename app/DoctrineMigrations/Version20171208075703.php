<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208075703 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE larp_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE organizer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE player_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE character_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fos_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE characterGroup_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE characterOrganizer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE larp (id INT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sync_uuid VARCHAR(36) DEFAULT NULL, external_id INT DEFAULT NULL, synced_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sync_status VARCHAR(10) DEFAULT NULL, sync_errors VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_54AD5CF87E3C61F9 ON larp (owner_id)');
        $this->addSql('CREATE TABLE person (id INT NOT NULL, user_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, gender VARCHAR(10) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sync_uuid VARCHAR(36) DEFAULT NULL, external_id INT DEFAULT NULL, synced_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sync_status VARCHAR(10) DEFAULT NULL, sync_errors VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176A76ED395 ON person (user_id)');
        $this->addSql('CREATE TABLE organizer (id INT NOT NULL, larp_id INT DEFAULT NULL, person_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_99D4717363FF2A01 ON organizer (larp_id)');
        $this->addSql('CREATE INDEX IDX_99D47173217BBB47 ON organizer (person_id)');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, larp_id INT DEFAULT NULL, person_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98197A6563FF2A01 ON player (larp_id)');
        $this->addSql('CREATE INDEX IDX_98197A65217BBB47 ON player (person_id)');
        $this->addSql('CREATE TABLE character (id INT NOT NULL, larp_id INT DEFAULT NULL, player_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_937AB03463FF2A01 ON character (larp_id)');
        $this->addSql('CREATE INDEX IDX_937AB03499E6F5DF ON character (player_id)');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, larp_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DC044C563FF2A01 ON "group" (larp_id)');
        $this->addSql('CREATE TABLE fos_user (id INT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479C05FB297 ON fos_user (confirmation_token)');
        $this->addSql('COMMENT ON COLUMN fos_user.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE characterGroup (id INT NOT NULL, character_id INT DEFAULT NULL, group_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E6D80F421136BE75 ON characterGroup (character_id)');
        $this->addSql('CREATE INDEX IDX_E6D80F42FE54D947 ON characterGroup (group_id)');
        $this->addSql('CREATE TABLE characterOrganizer (id INT NOT NULL, character_id INT DEFAULT NULL, organizer_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DE45F211136BE75 ON characterOrganizer (character_id)');
        $this->addSql('CREATE INDEX IDX_6DE45F21876C4DDA ON characterOrganizer (organizer_id)');
        $this->addSql('ALTER TABLE larp ADD CONSTRAINT FK_54AD5CF87E3C61F9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D4717363FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D47173217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6563FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03463FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C563FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterGroup ADD CONSTRAINT FK_E6D80F421136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterGroup ADD CONSTRAINT FK_E6D80F42FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterOrganizer ADD CONSTRAINT FK_6DE45F211136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterOrganizer ADD CONSTRAINT FK_6DE45F21876C4DDA FOREIGN KEY (organizer_id) REFERENCES organizer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE organizer DROP CONSTRAINT FK_99D4717363FF2A01');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A6563FF2A01');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03463FF2A01');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C563FF2A01');
        $this->addSql('ALTER TABLE larp DROP CONSTRAINT FK_54AD5CF87E3C61F9');
        $this->addSql('ALTER TABLE organizer DROP CONSTRAINT FK_99D47173217BBB47');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65217BBB47');
        $this->addSql('ALTER TABLE characterOrganizer DROP CONSTRAINT FK_6DE45F21876C4DDA');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03499E6F5DF');
        $this->addSql('ALTER TABLE characterGroup DROP CONSTRAINT FK_E6D80F421136BE75');
        $this->addSql('ALTER TABLE characterOrganizer DROP CONSTRAINT FK_6DE45F211136BE75');
        $this->addSql('ALTER TABLE characterGroup DROP CONSTRAINT FK_E6D80F42FE54D947');
        $this->addSql('ALTER TABLE person DROP CONSTRAINT FK_34DCD176A76ED395');
        $this->addSql('DROP SEQUENCE larp_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE organizer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE player_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE character_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fos_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE characterGroup_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE characterOrganizer_id_seq CASCADE');
        $this->addSql('DROP TABLE larp');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE organizer');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE characterGroup');
        $this->addSql('DROP TABLE characterOrganizer');
    }
}
