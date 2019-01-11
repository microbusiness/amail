<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190111141333 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('mail_job_status');
        $table->addColumn('id','integer', array('autoincrement' => true));
        $table->addColumn('code','string');
        $table->addColumn('name','string');
        $table->setPrimaryKey(array('id'));

        $table = $schema->createTable('mail_job');
        $table->addColumn('id','integer', array('autoincrement' => true));
        $table->addColumn('mail_job_status_id','integer');
        $table->addColumn('email','string');
        $table->addColumn('subject','string');
        $table->addColumn('sender_email','string');
        $table->addColumn('body_data','text');
        $table->addColumn('template','text');
        $table->addColumn('created_at','datetime');
        $table->addColumn('updated_at','datetime');
        $table->setPrimaryKey(array('id'));
        $table->addForeignKeyConstraint($schema->getTable('mail_job_status'), array('mail_job_status_id'), array('id'), array('NO ACTION', 'RESTRICT'),'fk_mail_job_mail_job_status');


    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('mail_job');
        $schema->dropTable('mail_job_status');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema) : void
    {
        $conn = $this->connection;

        $sql = "INSERT INTO mail_job_status (name, code) VALUES ('new', 'Новый') ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sql = "INSERT INTO mail_job_status (name, code) VALUES ('process', 'В процессе') ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sql = "INSERT INTO mail_job_status (name, code) VALUES ('complete', 'Отослано') ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }
}
