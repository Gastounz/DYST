<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129144835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE record CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F8697D13');
        $this->addSql('DROP INDEX UNIQ_8D93D649F8697D13 ON user');
        $this->addSql('ALTER TABLE user ADD adress VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, CHANGE comment_id post_code INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE record CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `user` DROP adress, DROP city, CHANGE post_code comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F8697D13 ON `user` (comment_id)');
    }
}
