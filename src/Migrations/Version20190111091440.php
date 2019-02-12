<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190111091440 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('system_params');
        $table->addColumn('id','integer', array('autoincrement' => true));
        $table->addColumn('code','string');
        $table->addColumn('data','text');
        $table->setPrimaryKey(array('id'));
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('system_params');
    }
}
