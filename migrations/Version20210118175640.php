<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118175640 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domain_expertise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE git_repo (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, license_name VARCHAR(50) DEFAULT NULL, stars_count INT DEFAULT NULL, forks_count INT DEFAULT NULL, INDEX IDX_F6CA0CD3166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mailing_list (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_15C473AF166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE more_info (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_31AE29F0166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programming_language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, domain_expertise_id INT NOT NULL, technical_expertise_id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, objective VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, organization VARCHAR(100) DEFAULT NULL, website VARCHAR(50) DEFAULT NULL, bug_tracking VARCHAR(255) DEFAULT NULL, documentation VARCHAR(255) DEFAULT NULL, project_data_file VARCHAR(255) DEFAULT NULL, project_logo VARCHAR(255) DEFAULT NULL, INDEX IDX_2FB3D0EE454E474 (domain_expertise_id), INDEX IDX_2FB3D0EE7A810A65 (technical_expertise_id), INDEX IDX_2FB3D0EE7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_programming_language (project_id INT NOT NULL, programming_language_id INT NOT NULL, INDEX IDX_E1C68A56166D1F9C (project_id), INDEX IDX_E1C68A56A2574C1E (programming_language_id), PRIMARY KEY(project_id, programming_language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_topic (project_id INT NOT NULL, topic_id INT NOT NULL, INDEX IDX_8E15D785166D1F9C (project_id), INDEX IDX_8E15D7851F55203D (topic_id), PRIMARY KEY(project_id, topic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technical_expertise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE git_repo ADD CONSTRAINT FK_F6CA0CD3166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE mailing_list ADD CONSTRAINT FK_15C473AF166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE more_info ADD CONSTRAINT FK_31AE29F0166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE454E474 FOREIGN KEY (domain_expertise_id) REFERENCES domain_expertise (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7A810A65 FOREIGN KEY (technical_expertise_id) REFERENCES technical_expertise (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_programming_language ADD CONSTRAINT FK_E1C68A56166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_programming_language ADD CONSTRAINT FK_E1C68A56A2574C1E FOREIGN KEY (programming_language_id) REFERENCES programming_language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_topic ADD CONSTRAINT FK_8E15D785166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_topic ADD CONSTRAINT FK_8E15D7851F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) ON DELETE CASCADE');
    
        include('_postUp/index.php');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE454E474');
        $this->addSql('ALTER TABLE project_programming_language DROP FOREIGN KEY FK_E1C68A56A2574C1E');
        $this->addSql('ALTER TABLE git_repo DROP FOREIGN KEY FK_F6CA0CD3166D1F9C');
        $this->addSql('ALTER TABLE mailing_list DROP FOREIGN KEY FK_15C473AF166D1F9C');
        $this->addSql('ALTER TABLE more_info DROP FOREIGN KEY FK_31AE29F0166D1F9C');
        $this->addSql('ALTER TABLE project_programming_language DROP FOREIGN KEY FK_E1C68A56166D1F9C');
        $this->addSql('ALTER TABLE project_topic DROP FOREIGN KEY FK_8E15D785166D1F9C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7A810A65');
        $this->addSql('ALTER TABLE project_topic DROP FOREIGN KEY FK_8E15D7851F55203D');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7E3C61F9');
        $this->addSql('DROP TABLE domain_expertise');
        $this->addSql('DROP TABLE git_repo');
        $this->addSql('DROP TABLE mailing_list');
        $this->addSql('DROP TABLE more_info');
        $this->addSql('DROP TABLE programming_language');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_programming_language');
        $this->addSql('DROP TABLE project_topic');
        $this->addSql('DROP TABLE technical_expertise');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE user');
    }
}
