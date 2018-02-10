<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20180114203756 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE estimate (id INT UNSIGNED AUTO_INCREMENT NOT NULL, customer_id INT UNSIGNED NOT NULL, code VARCHAR(255) NOT NULL, timestamp INT UNSIGNED NOT NULL, date DATE NOT NULL, deadline DATE NOT NULL, total_price DOUBLE PRECISION NOT NULL, state VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_D2EA46079395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimate_line (id INT UNSIGNED AUTO_INCREMENT NOT NULL, estimate_id INT UNSIGNED DEFAULT NULL, title VARCHAR(300) NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit_price DOUBLE PRECISION NOT NULL, total_price DOUBLE PRECISION NOT NULL, INDEX IDX_9715EDF785F23082 (estimate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT UNSIGNED AUTO_INCREMENT NOT NULL, company VARCHAR(100) DEFAULT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA46079395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE estimate_line ADD CONSTRAINT FK_9715EDF785F23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE estimate_line DROP FOREIGN KEY FK_9715EDF785F23082');
        $this->addSql('ALTER TABLE estimate DROP FOREIGN KEY FK_D2EA46079395C3F3');
        $this->addSql('DROP TABLE estimate');
        $this->addSql('DROP TABLE estimate_line');
        $this->addSql('DROP TABLE customer');
    }
}
