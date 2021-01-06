<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105165129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_programming_language (project_id INT NOT NULL, programming_language_id INT NOT NULL, INDEX IDX_E1C68A56166D1F9C (project_id), INDEX IDX_E1C68A56A2574C1E (programming_language_id), PRIMARY KEY(project_id, programming_language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_programming_language ADD CONSTRAINT FK_E1C68A56166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_programming_language ADD CONSTRAINT FK_E1C68A56A2574C1E FOREIGN KEY (programming_language_id) REFERENCES programming_language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_programming_language');
    }
}
