<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250918104028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conference_tag (conference_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(conference_id, tag_id), CONSTRAINT FK_6B8B769F604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6B8B769FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6B8B769F604B8382 ON conference_tag (conference_id)');
        $this->addSql('CREATE INDEX IDX_6B8B769FBAD26311 ON conference_tag (tag_id)');
        $this->addSql('CREATE TABLE conference_skill (conference_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(conference_id, skill_id), CONSTRAINT FK_5429230E604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5429230E5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5429230E604B8382 ON conference_skill (conference_id)');
        $this->addSql('CREATE INDEX IDX_5429230E5585C142 ON conference_skill (skill_id)');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE TABLE volunteer_profile (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, for_user_id INTEGER NOT NULL, CONSTRAINT FK_5FBFB5379B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FBFB5379B5BB4B8 ON volunteer_profile (for_user_id)');
        $this->addSql('CREATE TABLE volunteer_profile_skill (volunteer_profile_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(volunteer_profile_id, skill_id), CONSTRAINT FK_37AC8FDB8509C29A FOREIGN KEY (volunteer_profile_id) REFERENCES volunteer_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_37AC8FDB5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_37AC8FDB8509C29A ON volunteer_profile_skill (volunteer_profile_id)');
        $this->addSql('CREATE INDEX IDX_37AC8FDB5585C142 ON volunteer_profile_skill (skill_id)');
        $this->addSql('CREATE TABLE volunteer_profile_tag (volunteer_profile_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(volunteer_profile_id, tag_id), CONSTRAINT FK_7802AC888509C29A FOREIGN KEY (volunteer_profile_id) REFERENCES volunteer_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7802AC88BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7802AC888509C29A ON volunteer_profile_tag (volunteer_profile_id)');
        $this->addSql('CREATE INDEX IDX_7802AC88BAD26311 ON volunteer_profile_tag (tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE conference_tag');
        $this->addSql('DROP TABLE conference_skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE volunteer_profile');
        $this->addSql('DROP TABLE volunteer_profile_skill');
        $this->addSql('DROP TABLE volunteer_profile_tag');
    }
}
