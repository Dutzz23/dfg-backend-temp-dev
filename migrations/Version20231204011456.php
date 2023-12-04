<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204011456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_forms (user_form_id BIGINT NOT NULL, form_values_id BIGINT NOT NULL, INDEX IDX_1A48BFBE7FB8577 (user_form_id), INDEX IDX_1A48BFBEAE50E056 (form_values_id), PRIMARY KEY(user_form_id, form_values_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_forms ADD CONSTRAINT FK_1A48BFBE7FB8577 FOREIGN KEY (user_form_id) REFERENCES user_forms (id)');
        $this->addSql('ALTER TABLE users_forms ADD CONSTRAINT FK_1A48BFBEAE50E056 FOREIGN KEY (form_values_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE user_form_values DROP FOREIGN KEY FK_915251DD7FB8577');
        $this->addSql('ALTER TABLE user_form_values DROP FOREIGN KEY FK_915251DDAE50E056');
        $this->addSql('DROP TABLE user_form_values');
        $this->addSql('ALTER TABLE form ADD description VARCHAR(255) NOT NULL, CHANGE string name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE form_item ADD name VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_form_values (user_form_id BIGINT NOT NULL, form_values_id BIGINT NOT NULL, INDEX IDX_915251DDAE50E056 (form_values_id), INDEX IDX_915251DD7FB8577 (user_form_id), PRIMARY KEY(user_form_id, form_values_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_form_values ADD CONSTRAINT FK_915251DD7FB8577 FOREIGN KEY (user_form_id) REFERENCES user_forms (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_form_values ADD CONSTRAINT FK_915251DDAE50E056 FOREIGN KEY (form_values_id) REFERENCES form_values (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE users_forms DROP FOREIGN KEY FK_1A48BFBE7FB8577');
        $this->addSql('ALTER TABLE users_forms DROP FOREIGN KEY FK_1A48BFBEAE50E056');
        $this->addSql('DROP TABLE users_forms');
        $this->addSql('ALTER TABLE form_item DROP name, DROP description');
        $this->addSql('ALTER TABLE form ADD string VARCHAR(255) NOT NULL, DROP name, DROP description');
    }
}
