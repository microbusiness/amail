<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181223141134 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE fos_user (id SERIAL NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, fullname VARCHAR(255), phone VARCHAR(255), email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, locked BOOLEAN DEFAULT FALSE, expired BOOLEAN DEFAULT FALSE, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, credentials_expired BOOLEAN DEFAULT FALSE, credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
        $this->addSql('COMMENT ON COLUMN fos_user.roles IS \'(DC2Type:array)\'');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('DROP TABLE fos_user');

    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema) : void
    {
        $now = new \DateTime();
        $conn = $this->connection;

        $sql = "select id from fos_user where username='system' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $systemUserId=false;
        while ($row = $stmt->fetch())
        {
            $systemUserId=$row['id'];
        }

        if (!$systemUserId)
        {
            $sql = "INSERT INTO fos_user
                  (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at)
                  VALUES(-1, 'system', 'system', '', '', false, '', '', '2000-01-01 00:00:00.000', false, false, NULL, NULL, NULL, 'a:0:{}', false, NULL); ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
    }
}
