<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180111193830 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE character_data_definition ADD required BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE character_data_definition DROP min');
        $this->addSql('ALTER TABLE character_data_definition DROP max');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE character_data_definition ADD min INT NOT NULL');
        $this->addSql('ALTER TABLE character_data_definition ADD max INT NOT NULL');
        $this->addSql('ALTER TABLE character_data_definition DROP required');
    }
}
