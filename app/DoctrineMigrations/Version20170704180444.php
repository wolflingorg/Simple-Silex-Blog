<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170704180444 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user ( id VARCHAR(36) NOT NULL PRIMARY key, name VARCHAR(256) NULL ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE photo ( id VARCHAR(36) NOT NULL PRIMARY key, src VARCHAR(1024) NOT NULL, user VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->addSql('CREATE INDEX photo_user_fk ON photo (user);');
        $this->addSql('CREATE TABLE post ( id VARCHAR(36) NOT NULL PRIMARY key, title VARCHAR(256) NOT NULL, body text NULL, is_published TINYINT(1) DEFAULT 0 NULL, user VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->addSql('CREATE INDEX post_user_fk ON post (user);');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT photo_user_fk FOREIGN key (user) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE;');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT post_user_fk FOREIGN key (user) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY photo_user_fk');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY post_user_fk');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
    }
}
