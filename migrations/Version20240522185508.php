<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522185508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_favorite_wine (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, wine_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5684D007A76ED395 (user_id), INDEX IDX_5684D00728A2BD76 (wine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wine (id INT AUTO_INCREMENT NOT NULL, winery_id INT DEFAULT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, alcohol_content DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, grapes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', year INT DEFAULT NULL, ingredients LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_560C646832FAE8E8 (winery_id), INDEX IDX_560C64687E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE winery (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, description LONGTEXT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, number_of_employees INT DEFAULT NULL, UNIQUE INDEX UNIQ_92F2D2F1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_favorite_wine ADD CONSTRAINT FK_5684D007A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_favorite_wine ADD CONSTRAINT FK_5684D00728A2BD76 FOREIGN KEY (wine_id) REFERENCES wine (id)');
        $this->addSql('ALTER TABLE wine ADD CONSTRAINT FK_560C646832FAE8E8 FOREIGN KEY (winery_id) REFERENCES winery (id)');
        $this->addSql('ALTER TABLE wine ADD CONSTRAINT FK_560C64687E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE winery ADD CONSTRAINT FK_92F2D2F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_favorite_wine DROP FOREIGN KEY FK_5684D007A76ED395');
        $this->addSql('ALTER TABLE user_favorite_wine DROP FOREIGN KEY FK_5684D00728A2BD76');
        $this->addSql('ALTER TABLE wine DROP FOREIGN KEY FK_560C646832FAE8E8');
        $this->addSql('ALTER TABLE wine DROP FOREIGN KEY FK_560C64687E3C61F9');
        $this->addSql('ALTER TABLE winery DROP FOREIGN KEY FK_92F2D2F1A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_favorite_wine');
        $this->addSql('DROP TABLE wine');
        $this->addSql('DROP TABLE winery');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
