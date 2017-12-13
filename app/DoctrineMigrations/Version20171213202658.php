<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171213202658 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE external_importation_progress ADD synchronization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE external_importation_progress ADD CONSTRAINT FK_DFCADB32C469E18 FOREIGN KEY (synchronization_id) REFERENCES external_synchronization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DFCADB32C469E18 ON external_importation_progress (synchronization_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE external_importation_progress DROP CONSTRAINT FK_DFCADB32C469E18');
        $this->addSql('DROP INDEX IDX_DFCADB32C469E18');
        $this->addSql('ALTER TABLE external_importation_progress DROP synchronization_id');
    }
}
