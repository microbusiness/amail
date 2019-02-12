<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190112134148 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE mail_job ADD COLUMN code UUID NOT NULL DEFAULT md5(random()::text || clock_timestamp()::text)::uuid");

    }

    public function down(Schema $schema) : void
    {
        $schema->getTable('mail_job')->dropColumn('code');

    }
}
