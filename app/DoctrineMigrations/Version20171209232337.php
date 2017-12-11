<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171209232337 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE UNIQUE INDEX organizer_larp_person_idx ON organizer (larp_id, person_id)');

        $this->addSql('DROP INDEX larp_person_idx');
        $this->addSql('CREATE UNIQUE INDEX player_larp_person_idx ON player (larp_id, person_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX organizer_larp_person_idx');
        $this->addSql('DROP INDEX player_larp_person_idx');
        $this->addSql('CREATE UNIQUE INDEX larp_person_idx ON player (larp_id, person_id)');
    }
}
