<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208131118 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE organizer ADD sync_uuid VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE organizer ADD external_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organizer ADD synced_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE organizer ADD sync_status VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE organizer ADD sync_errors VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE organizer DROP sync_uuid');
        $this->addSql('ALTER TABLE organizer DROP external_id');
        $this->addSql('ALTER TABLE organizer DROP synced_at');
        $this->addSql('ALTER TABLE organizer DROP sync_status');
        $this->addSql('ALTER TABLE organizer DROP sync_errors');
    }
}
