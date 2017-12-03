<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171203105519 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE larp ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE larp ADD CONSTRAINT FK_54AD5CF87E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_54AD5CF87E3C61F9 ON larp (owner_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE larp DROP CONSTRAINT FK_54AD5CF87E3C61F9');
        $this->addSql('DROP INDEX IDX_54AD5CF87E3C61F9');
        $this->addSql('ALTER TABLE larp DROP owner_id');
    }
}
