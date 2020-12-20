<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220022407 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE git_repo (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_F6CA0CD3166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mailing_list (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_15C473AF166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE more_info (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_31AE29F0166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, domain_expertise_id INT NOT NULL, technical_expertise_id INT NOT NULL, name VARCHAR(100) NOT NULL, objective VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, organization VARCHAR(100) DEFAULT NULL, website VARCHAR(50) DEFAULT NULL, bug_tracking VARCHAR(255) DEFAULT NULL, documentation VARCHAR(255) DEFAULT NULL, INDEX IDX_2FB3D0EE454E474 (domain_expertise_id), INDEX IDX_2FB3D0EE7A810A65 (technical_expertise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE git_repo ADD CONSTRAINT FK_F6CA0CD3166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE mailing_list ADD CONSTRAINT FK_15C473AF166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE more_info ADD CONSTRAINT FK_31AE29F0166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE454E474 FOREIGN KEY (domain_expertise_id) REFERENCES domain_expertise (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7A810A65 FOREIGN KEY (technical_expertise_id) REFERENCES technical_expertise (id)');
    
        include('_postUp/index.php');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE git_repo DROP FOREIGN KEY FK_F6CA0CD3166D1F9C');
        $this->addSql('ALTER TABLE mailing_list DROP FOREIGN KEY FK_15C473AF166D1F9C');
        $this->addSql('ALTER TABLE more_info DROP FOREIGN KEY FK_31AE29F0166D1F9C');
        $this->addSql('DROP TABLE git_repo');
        $this->addSql('DROP TABLE mailing_list');
        $this->addSql('DROP TABLE more_info');
        $this->addSql('DROP TABLE project');
    }
}
