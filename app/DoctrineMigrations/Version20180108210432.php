<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180108210432 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE character_data_section_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE character_data_section (id INT NOT NULL, larp_id INT DEFAULT NULL, position INT NOT NULL, label VARCHAR(255) NOT NULL, class INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_961CA97763FF2A01 ON character_data_section (larp_id)');
        $this->addSql('ALTER TABLE character_data_section ADD CONSTRAINT FK_961CA97763FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE character_data_section_id_seq CASCADE');
        $this->addSql('DROP TABLE character_data_section');
    }
}
