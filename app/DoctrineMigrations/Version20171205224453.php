<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171205224453 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE person (id INT NOT NULL, user_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176A76ED395 ON person (user_id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE larp DROP CONSTRAINT FK_54AD5CF87E3C61F9');
        $this->addSql('ALTER TABLE larp ADD CONSTRAINT FK_54AD5CF87E3C61F9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organizer DROP CONSTRAINT fk_99d47173a76ed395');
        $this->addSql('DROP INDEX idx_99d47173a76ed395');
        $this->addSql('ALTER TABLE organizer DROP firstname');
        $this->addSql('ALTER TABLE organizer DROP lastname');
        $this->addSql('ALTER TABLE organizer RENAME COLUMN user_id TO person_id');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D47173217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_99D47173217BBB47 ON organizer (person_id)');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT fk_98197a65a76ed395');
        $this->addSql('DROP INDEX idx_98197a65a76ed395');
        $this->addSql('ALTER TABLE player DROP firstname');
        $this->addSql('ALTER TABLE player DROP lastname');
        $this->addSql('ALTER TABLE player RENAME COLUMN user_id TO person_id');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_98197A65217BBB47 ON player (person_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE larp DROP CONSTRAINT FK_54AD5CF87E3C61F9');
        $this->addSql('ALTER TABLE organizer DROP CONSTRAINT FK_99D47173217BBB47');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65217BBB47');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP INDEX IDX_98197A65217BBB47');
        $this->addSql('ALTER TABLE player ADD firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD lastname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player RENAME COLUMN person_id TO user_id');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT fk_98197a65a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_98197a65a76ed395 ON player (user_id)');
        $this->addSql('ALTER TABLE larp DROP CONSTRAINT fk_54ad5cf87e3c61f9');
        $this->addSql('ALTER TABLE larp ADD CONSTRAINT fk_54ad5cf87e3c61f9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX IDX_99D47173217BBB47');
        $this->addSql('ALTER TABLE organizer ADD firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organizer ADD lastname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organizer RENAME COLUMN person_id TO user_id');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT fk_99d47173a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_99d47173a76ed395 ON organizer (user_id)');
    }
}
