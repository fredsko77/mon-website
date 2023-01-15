<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111044209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_tracking (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nb_call_api_request INT DEFAULT NULL, last_request_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7B47A893A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_stack (project_id INT NOT NULL, stack_id INT NOT NULL, INDEX IDX_52FD72F4166D1F9C (project_id), INDEX IDX_52FD72F437C70060 (stack_id), PRIMARY KEY(project_id, stack_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_tracking ADD CONSTRAINT FK_7B47A893A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_stack ADD CONSTRAINT FK_52FD72F4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_stack ADD CONSTRAINT FK_52FD72F437C70060 FOREIGN KEY (stack_id) REFERENCES stack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76E7A1254A ON document (contact_id)');
        $this->addSql('ALTER TABLE page ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62016A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_140AB62016A2B381 ON page (book_id)');
        $this->addSql('ALTER TABLE project ADD user_id INT DEFAULT NULL, ADD visibility VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEA76ED395 ON project (user_id)');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(100) DEFAULT NULL, ADD lastname VARCHAR(100) DEFAULT NULL, ADD username VARCHAR(100) DEFAULT NULL, DROP fullname');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_tracking DROP FOREIGN KEY FK_7B47A893A76ED395');
        $this->addSql('ALTER TABLE project_stack DROP FOREIGN KEY FK_52FD72F4166D1F9C');
        $this->addSql('ALTER TABLE project_stack DROP FOREIGN KEY FK_52FD72F437C70060');
        $this->addSql('DROP TABLE api_tracking');
        $this->addSql('DROP TABLE project_stack');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76E7A1254A');
        $this->addSql('DROP INDEX IDX_D8698A76E7A1254A ON document');
        $this->addSql('ALTER TABLE document DROP contact_id');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62016A2B381');
        $this->addSql('DROP INDEX IDX_140AB62016A2B381 ON page');
        $this->addSql('ALTER TABLE page DROP book_id');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('DROP INDEX IDX_2FB3D0EEA76ED395 ON project');
        $this->addSql('ALTER TABLE project DROP user_id, DROP visibility');
        $this->addSql('ALTER TABLE user ADD fullname VARCHAR(150) DEFAULT NULL, DROP firstname, DROP lastname, DROP username');
    }
}
