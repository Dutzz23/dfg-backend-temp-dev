<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204024721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_widget_parameter_value DROP FOREIGN KEY FK_D29843D5A7C41D6F');
        $this->addSql('DROP INDEX IDX_D29843D5A7C41D6F ON form_widget_parameter_value');
        $this->addSql('ALTER TABLE form_widget_parameter_value CHANGE option_id parameter_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE form_widget_parameter_value ADD CONSTRAINT FK_D29843D57C56DBD6 FOREIGN KEY (parameter_id) REFERENCES form_widget_parameter (id)');
        $this->addSql('CREATE INDEX IDX_D29843D57C56DBD6 ON form_widget_parameter_value (parameter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_widget_parameter_value DROP FOREIGN KEY FK_D29843D57C56DBD6');
        $this->addSql('DROP INDEX IDX_D29843D57C56DBD6 ON form_widget_parameter_value');
        $this->addSql('ALTER TABLE form_widget_parameter_value CHANGE parameter_id option_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE form_widget_parameter_value ADD CONSTRAINT FK_D29843D5A7C41D6F FOREIGN KEY (option_id) REFERENCES form_widget_parameter (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D29843D5A7C41D6F ON form_widget_parameter_value (option_id)');
    }
}
