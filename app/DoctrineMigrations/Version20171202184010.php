<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171202184010 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE player_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE characterOrganizer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE character_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE larp_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE characterGroup_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE organizer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, larp_id INT DEFAULT NULL, user_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98197A6563FF2A01 ON player (larp_id)');
        $this->addSql('CREATE INDEX IDX_98197A65A76ED395 ON player (user_id)');
        $this->addSql('CREATE TABLE characterOrganizer (id INT NOT NULL, character_id INT DEFAULT NULL, organizer_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DE45F211136BE75 ON characterOrganizer (character_id)');
        $this->addSql('CREATE INDEX IDX_6DE45F21876C4DDA ON characterOrganizer (organizer_id)');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, larp_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DC044C563FF2A01 ON "group" (larp_id)');
        $this->addSql('CREATE TABLE character (id INT NOT NULL, larp_id INT DEFAULT NULL, player_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_937AB03463FF2A01 ON character (larp_id)');
        $this->addSql('CREATE INDEX IDX_937AB03499E6F5DF ON character (player_id)');
        $this->addSql('CREATE TABLE larp (id INT NOT NULL, name VARCHAR(255) NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE characterGroup (id INT NOT NULL, character_id INT DEFAULT NULL, group_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E6D80F421136BE75 ON characterGroup (character_id)');
        $this->addSql('CREATE INDEX IDX_E6D80F42FE54D947 ON characterGroup (group_id)');
        $this->addSql('CREATE TABLE organizer (id INT NOT NULL, larp_id INT DEFAULT NULL, user_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_99D4717363FF2A01 ON organizer (larp_id)');
        $this->addSql('CREATE INDEX IDX_99D47173A76ED395 ON organizer (user_id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6563FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterOrganizer ADD CONSTRAINT FK_6DE45F211136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterOrganizer ADD CONSTRAINT FK_6DE45F21876C4DDA FOREIGN KEY (organizer_id) REFERENCES organizer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C563FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03463FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterGroup ADD CONSTRAINT FK_E6D80F421136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characterGroup ADD CONSTRAINT FK_E6D80F42FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D4717363FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D47173A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03499E6F5DF');
        $this->addSql('ALTER TABLE characterGroup DROP CONSTRAINT FK_E6D80F42FE54D947');
        $this->addSql('ALTER TABLE characterOrganizer DROP CONSTRAINT FK_6DE45F211136BE75');
        $this->addSql('ALTER TABLE characterGroup DROP CONSTRAINT FK_E6D80F421136BE75');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A6563FF2A01');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C563FF2A01');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03463FF2A01');
        $this->addSql('ALTER TABLE organizer DROP CONSTRAINT FK_99D4717363FF2A01');
        $this->addSql('ALTER TABLE characterOrganizer DROP CONSTRAINT FK_6DE45F21876C4DDA');
        $this->addSql('DROP SEQUENCE player_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE characterOrganizer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE character_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE larp_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE characterGroup_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE organizer_id_seq CASCADE');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE characterOrganizer');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE larp');
        $this->addSql('DROP TABLE characterGroup');
        $this->addSql('DROP TABLE organizer');
    }
}
