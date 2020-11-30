<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201130045746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $domain_expertise = array(
            'Agriculture & Farming',
            'Arts & Heritage Crafts',
            'Banking, Finance and Insurance',
            'Charity & Philanthropy',
            'Disaster Relief',
            'Economy',
            'Education',
            'Elections & Governance',
            'Emergency Response',
            'Environment, Forestry & Wildlife Conservation',
            'Fisheries & Aquatic Resources',
            'Foreign Affairs',
            'Health, Nutrition & Indigenous Medicine',
            'Inland Revenue & Taxes',
            'Justice, Legal & Legislation',
            'Labor',
            'Land',
            'Law Enforcement & Defence',
            'Local Government & Provincial Councils',
            'Manufacturing',
            'Media & Communications',
            'Official Languages',
            'Open Data',
            'Pensions, Social Security & Welfare',
            'Ports & Shipping',
            'Power & Energy',
            'Productivity Tools',
            'Public administration',
            'Public Utilities',
            'Real estate, Housing, Construction',
            'Right to Information',
            'Roads, Highways, Infrastructure',
            'Science, Technology & Research',
            'Sports',
            'Telecommunications',
            'Transportation',
            'Travel & Tourism',
            'Waste Management & Disposal',
            'Women & Children'
        );

        $technical_expertise = array(
            'Cloud',
            'Data Analytics & Visualization',
            'Database',
            'Developer Tools',
            'Enterprise',
            'Entertainment',
            'Games',
            'Graphics, Video, Audio',
            'Internationalization or Localization',
            'Internet of Things (IoT)',
            'Location & Maps',
            'Machine Learning, Neural Networks & AI',
            'Mobile',
            'Networking',
            'Programming',
            'Productivity',
            'Samples & Examples',
            'Security & Compliance Testing',
            'Utilities',
            'Web'
        );

        $programming_language = array(
            'Ballerina',
            'C',
            'C#',
            'C++',
            'CSS',
            'Dart',
            'Go',
            'Haskell',
            'HTML',
            'Java',
            'Javascript',
            'Lua',
            'Objective-C',
            'Perl',
            'PHP',
            'Python',
            'R',
            'Ruby',
            'Rust',
            'Shell',
            'SQL',
            'Swift',
            'Typescript'
        );

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE domain_expertise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programming_language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technical_expertise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    
        
        foreach($domain_expertise as $de) {
            $this->addSql('INSERT INTO domain_expertise (name) VALUES (?)', array($de));
        }

        foreach($programming_language as $pl) {
            $this->addSql('INSERT INTO programming_language (name) VALUES (?)', array($pl));
        }

        foreach($technical_expertise as $te) {
            $this->addSql('INSERT INTO technical_expertise (name) VALUES (?)', array($te));
        }
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE domain_expertise');
        $this->addSql('DROP TABLE programming_language');
        $this->addSql('DROP TABLE technical_expertise');
    }
}
