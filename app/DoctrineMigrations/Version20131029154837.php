<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131029154837 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE programs (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE programs_users (program_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C2E624C73EB8070A (program_id), INDEX IDX_C2E624C7A76ED395 (user_id), PRIMARY KEY(program_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE proposed_answers (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_B747F42F1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user_answers (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_8DDD80C1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE surveys (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, email_content LONGTEXT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, survey_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, type VARCHAR(25) NOT NULL, INDEX IDX_8ADC54D5B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE programs_users ADD CONSTRAINT FK_C2E624C73EB8070A FOREIGN KEY (program_id) REFERENCES programs (id)");
        $this->addSql("ALTER TABLE programs_users ADD CONSTRAINT FK_C2E624C7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)");
        $this->addSql("ALTER TABLE proposed_answers ADD CONSTRAINT FK_B747F42F1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)");
        $this->addSql("ALTER TABLE user_answers ADD CONSTRAINT FK_8DDD80C1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)");
        $this->addSql("ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5B3FE509D FOREIGN KEY (survey_id) REFERENCES surveys (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE programs_users DROP FOREIGN KEY FK_C2E624C73EB8070A");
        $this->addSql("ALTER TABLE programs_users DROP FOREIGN KEY FK_C2E624C7A76ED395");
        $this->addSql("ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5B3FE509D");
        $this->addSql("ALTER TABLE proposed_answers DROP FOREIGN KEY FK_B747F42F1E27F6BF");
        $this->addSql("ALTER TABLE user_answers DROP FOREIGN KEY FK_8DDD80C1E27F6BF");
        $this->addSql("DROP TABLE programs");
        $this->addSql("DROP TABLE programs_users");
        $this->addSql("DROP TABLE users");
        $this->addSql("DROP TABLE proposed_answers");
        $this->addSql("DROP TABLE user_answers");
        $this->addSql("DROP TABLE surveys");
        $this->addSql("DROP TABLE questions");
    }
}
