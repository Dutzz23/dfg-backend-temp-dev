<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218024009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_item ADD desired_value VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE form_values ADD user_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE form_values ADD CONSTRAINT FK_38499848A76ED395 FOREIGN KEY (user_id) REFERENCES form (id)');
        $this->addSql('CREATE INDEX IDX_38499848A76ED395 ON form_values (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_values DROP FOREIGN KEY FK_38499848A76ED395');
        $this->addSql('DROP INDEX IDX_38499848A76ED395 ON form_values');
        $this->addSql('ALTER TABLE form_values DROP user_id');
        $this->addSql('ALTER TABLE form_item DROP desired_value, CHANGE description description VARCHAR(255) NOT NULL');
    }
}
