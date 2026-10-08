<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261008074350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fill NULL volunteer_profile.updated_at values';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE volunteer_profile SET updated_at = CURRENT_TIMESTAMP WHERE updated_at IS NULL');
    }

    public function down(Schema $schema): void
    {
        // Data backfill: the rows that were NULL before cannot be told apart, nothing to revert
    }
}
