<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261008141924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__volunteer_profile AS
            SELECT
              id,
              for_user_id,
              updated_at
            FROM
              volunteer_profile
        SQL);
        $this->addSql('DROP TABLE volunteer_profile');
        $this->addSql(<<<'SQL'
            CREATE TABLE volunteer_profile (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              for_user_id INTEGER NOT NULL,
              updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
              ,
              CONSTRAINT FK_5FBFB5379B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES user (id) ON
              UPDATE
                NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO volunteer_profile (id, for_user_id, updated_at)
            SELECT
              id,
              for_user_id,
              updated_at
            FROM
              __temp__volunteer_profile
        SQL);
        $this->addSql('DROP TABLE __temp__volunteer_profile');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FBFB5379B5BB4B8 ON volunteer_profile (for_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__volunteer_profile AS
            SELECT
              id,
              for_user_id,
              updated_at
            FROM
              volunteer_profile
        SQL);
        $this->addSql('DROP TABLE volunteer_profile');
        $this->addSql(<<<'SQL'
            CREATE TABLE volunteer_profile (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              for_user_id INTEGER NOT NULL,
              updated_at DATETIME DEFAULT NULL,
              CONSTRAINT FK_5FBFB5379B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO volunteer_profile (id, for_user_id, updated_at)
            SELECT
              id,
              for_user_id,
              updated_at
            FROM
              __temp__volunteer_profile
        SQL);
        $this->addSql('DROP TABLE __temp__volunteer_profile');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FBFB5379B5BB4B8 ON volunteer_profile (for_user_id)');
    }
}
