<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180114103845 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE calendar_month_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE calendar_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE calendar_month (id INT NOT NULL, calendar_id INT DEFAULT NULL, position INT NOT NULL, name VARCHAR(64) DEFAULT NULL, nb_days INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E2E21368A40A2C8 ON calendar_month (calendar_id)');
        $this->addSql('CREATE TABLE calendar (id INT NOT NULL, larp_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, diff_days_with_origin INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6EA9A14663FF2A01 ON calendar (larp_id)');
        $this->addSql('ALTER TABLE calendar_month ADD CONSTRAINT FK_E2E21368A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A14663FF2A01 FOREIGN KEY (larp_id) REFERENCES larp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE calendar_month DROP CONSTRAINT FK_E2E21368A40A2C8');
        $this->addSql('DROP SEQUENCE calendar_month_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE calendar_id_seq CASCADE');
        $this->addSql('DROP TABLE calendar_month');
        $this->addSql('DROP TABLE calendar');
    }
}
