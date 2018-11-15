<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115205244 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oc_skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oc_advert_skill (id INT AUTO_INCREMENT NOT NULL, advert_id INT NOT NULL, skill_id INT NOT NULL, level VARCHAR(255) NOT NULL, INDEX IDX_32EFF25BD07ECCB6 (advert_id), INDEX IDX_32EFF25B5585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oc_advert_skill ADD CONSTRAINT FK_32EFF25BD07ECCB6 FOREIGN KEY (advert_id) REFERENCES oc_advert (id)');
        $this->addSql('ALTER TABLE oc_advert_skill ADD CONSTRAINT FK_32EFF25B5585C142 FOREIGN KEY (skill_id) REFERENCES oc_skill (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oc_advert_skill DROP FOREIGN KEY FK_32EFF25B5585C142');
        $this->addSql('DROP TABLE oc_skill');
        $this->addSql('DROP TABLE oc_advert_skill');
    }
}
