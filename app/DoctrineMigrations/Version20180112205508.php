<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180112205508 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE character_data_definition_enum_category_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE character_data_definition_enum_category_item (id INT NOT NULL, category_id INT DEFAULT NULL, position INT NOT NULL, name VARCHAR(64) DEFAULT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ED60E59112469DE2 ON character_data_definition_enum_category_item (category_id)');
        $this->addSql('ALTER TABLE character_data_definition_enum_category_item ADD CONSTRAINT FK_ED60E59112469DE2 FOREIGN KEY (category_id) REFERENCES character_data_definition_enum_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE character_data_definition_enum_category_item_id_seq CASCADE');
        $this->addSql('DROP TABLE character_data_definition_enum_category_item');
    }
}
