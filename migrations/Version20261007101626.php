<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261007101626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer_profile ADD COLUMN updated_at DATETIME DEFAULT NULL');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__messenger_messages AS
            SELECT
              id,
              body,
              headers,
              queue_name,
              created_at,
              available_at,
              delivered_at
            FROM
              messenger_messages
        SQL);
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              body CLOB NOT NULL,
              headers CLOB NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
              ,
              available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
              ,
              delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO messenger_messages (
              id, body, headers, queue_name, created_at,
              available_at, delivered_at
            )
            SELECT
              id,
              body,
              headers,
              queue_name,
              created_at,
              available_at,
              delivered_at
            FROM
              __temp__messenger_messages
        SQL);
        $this->addSql('DROP TABLE __temp__messenger_messages');
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (
              queue_name, available_at, delivered_at,
              id
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__messenger_messages AS
            SELECT
              id,
              body,
              headers,
              queue_name,
              created_at,
              available_at,
              delivered_at
            FROM
              messenger_messages
        SQL);
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              body CLOB NOT NULL,
              headers CLOB NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
              ,
              available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
              ,
              delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO messenger_messages (
              id, body, headers, queue_name, created_at,
              available_at, delivered_at
            )
            SELECT
              id,
              body,
              headers,
              queue_name,
              created_at,
              available_at,
              delivered_at
            FROM
              __temp__messenger_messages
        SQL);
        $this->addSql('DROP TABLE __temp__messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__volunteer_profile AS
            SELECT
              id,
              for_user_id
            FROM
              volunteer_profile
        SQL);
        $this->addSql('DROP TABLE volunteer_profile');
        $this->addSql(<<<'SQL'
            CREATE TABLE volunteer_profile (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              for_user_id INTEGER NOT NULL,
              CONSTRAINT FK_5FBFB5379B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO volunteer_profile (id, for_user_id)
            SELECT
              id,
              for_user_id
            FROM
              __temp__volunteer_profile
        SQL);
        $this->addSql('DROP TABLE __temp__volunteer_profile');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FBFB5379B5BB4B8 ON volunteer_profile (for_user_id)');
    }
}
