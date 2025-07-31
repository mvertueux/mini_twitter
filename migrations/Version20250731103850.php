<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250731103850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE retweet ADD commentaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB3BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_45E67DB3BA9CD190 ON retweet (commentaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB3BA9CD190');
        $this->addSql('DROP INDEX IDX_45E67DB3BA9CD190 ON retweet');
        $this->addSql('ALTER TABLE retweet DROP commentaire_id');
    }
}
