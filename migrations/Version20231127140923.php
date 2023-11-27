<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127140923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form (id BIGINT AUTO_INCREMENT NOT NULL, string VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_items (form_id BIGINT NOT NULL, item_id BIGINT NOT NULL, INDEX IDX_62594D8E5FF69B7D (form_id), INDEX IDX_62594D8E126F525E (item_id), PRIMARY KEY(form_id, item_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_item (id BIGINT AUTO_INCREMENT NOT NULL, form_widget_id BIGINT DEFAULT NULL, INDEX IDX_8B3A21098AE83AEE (form_widget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_items_parameter_values (form_item_id BIGINT NOT NULL, parameter_value_id BIGINT NOT NULL, INDEX IDX_3EDC0CA723033265 (form_item_id), INDEX IDX_3EDC0CA71452663E (parameter_value_id), PRIMARY KEY(form_item_id, parameter_value_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_values (id BIGINT AUTO_INCREMENT NOT NULL, form_id BIGINT DEFAULT NULL, `values` JSON NOT NULL, INDEX IDX_384998485FF69B7D (form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_widget (id BIGINT AUTO_INCREMENT NOT NULL, form_type INT NOT NULL, ui_component_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_widget_parameter (id BIGINT AUTO_INCREMENT NOT NULL, string VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_widget_parameter_value (id BIGINT AUTO_INCREMENT NOT NULL, option_id BIGINT DEFAULT NULL, string VARCHAR(255) NOT NULL, INDEX IDX_D29843D5A7C41D6F (option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forms (id BIGINT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_1FFDE0AFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_form_values (user_form_id BIGINT NOT NULL, form_values_id BIGINT NOT NULL, INDEX IDX_915251DD7FB8577 (user_form_id), INDEX IDX_915251DDAE50E056 (form_values_id), PRIMARY KEY(user_form_id, form_values_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE form_items ADD CONSTRAINT FK_62594D8E5FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE form_items ADD CONSTRAINT FK_62594D8E126F525E FOREIGN KEY (item_id) REFERENCES form_item (id)');
        $this->addSql('ALTER TABLE form_item ADD CONSTRAINT FK_8B3A21098AE83AEE FOREIGN KEY (form_widget_id) REFERENCES form_widget (id)');
        $this->addSql('ALTER TABLE form_items_parameter_values ADD CONSTRAINT FK_3EDC0CA723033265 FOREIGN KEY (form_item_id) REFERENCES form_item (id)');
        $this->addSql('ALTER TABLE form_items_parameter_values ADD CONSTRAINT FK_3EDC0CA71452663E FOREIGN KEY (parameter_value_id) REFERENCES form_widget_parameter_value (id)');
        $this->addSql('ALTER TABLE form_values ADD CONSTRAINT FK_384998485FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE form_widget_parameter_value ADD CONSTRAINT FK_D29843D5A7C41D6F FOREIGN KEY (option_id) REFERENCES form_widget_parameter (id)');
        $this->addSql('ALTER TABLE user_forms ADD CONSTRAINT FK_1FFDE0AFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_form_values ADD CONSTRAINT FK_915251DD7FB8577 FOREIGN KEY (user_form_id) REFERENCES user_forms (id)');
        $this->addSql('ALTER TABLE user_form_values ADD CONSTRAINT FK_915251DDAE50E056 FOREIGN KEY (form_values_id) REFERENCES form_values (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_items DROP FOREIGN KEY FK_62594D8E5FF69B7D');
        $this->addSql('ALTER TABLE form_items DROP FOREIGN KEY FK_62594D8E126F525E');
        $this->addSql('ALTER TABLE form_item DROP FOREIGN KEY FK_8B3A21098AE83AEE');
        $this->addSql('ALTER TABLE form_items_parameter_values DROP FOREIGN KEY FK_3EDC0CA723033265');
        $this->addSql('ALTER TABLE form_items_parameter_values DROP FOREIGN KEY FK_3EDC0CA71452663E');
        $this->addSql('ALTER TABLE form_values DROP FOREIGN KEY FK_384998485FF69B7D');
        $this->addSql('ALTER TABLE form_widget_parameter_value DROP FOREIGN KEY FK_D29843D5A7C41D6F');
        $this->addSql('ALTER TABLE user_forms DROP FOREIGN KEY FK_1FFDE0AFA76ED395');
        $this->addSql('ALTER TABLE user_form_values DROP FOREIGN KEY FK_915251DD7FB8577');
        $this->addSql('ALTER TABLE user_form_values DROP FOREIGN KEY FK_915251DDAE50E056');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE form_items');
        $this->addSql('DROP TABLE form_item');
        $this->addSql('DROP TABLE form_items_parameter_values');
        $this->addSql('DROP TABLE form_values');
        $this->addSql('DROP TABLE form_widget');
        $this->addSql('DROP TABLE form_widget_parameter');
        $this->addSql('DROP TABLE form_widget_parameter_value');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_forms');
        $this->addSql('DROP TABLE user_form_values');
    }
}
