<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180108211528 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE character_data_definition ADD section_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character_data_definition ADD CONSTRAINT FK_EC282C3ED823E37A FOREIGN KEY (section_id) REFERENCES character_data_section (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EC282C3ED823E37A ON character_data_definition (section_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE character_data_definition DROP CONSTRAINT FK_EC282C3ED823E37A');
        $this->addSql('DROP INDEX IDX_EC282C3ED823E37A');
        $this->addSql('ALTER TABLE character_data_definition DROP section_id');
    }
}
