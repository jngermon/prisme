<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180130195852 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE calendar ADD format_global TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar ADD format_year TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar ADD format_day TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar_month ADD name_for_date VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar_month ADD format_day TEXT DEFAULT NULL');

        $this->addSql("UPDATE calendar SET format_global = '%day% %month% %year%', format_year = 'de l''an %y%', format_day = '{1}1er jour|]1,Inf[%d%ième jour'");
        $this->addSql('UPDATE calendar_month SET name_for_date = name');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE calendar DROP format_global');
        $this->addSql('ALTER TABLE calendar DROP format_year');
        $this->addSql('ALTER TABLE calendar DROP format_day');
        $this->addSql('ALTER TABLE calendar_month DROP name_for_date');
        $this->addSql('ALTER TABLE calendar_month DROP format_day');
    }
}
