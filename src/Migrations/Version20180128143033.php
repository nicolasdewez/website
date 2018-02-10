<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180128143033 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bill (id INT UNSIGNED AUTO_INCREMENT NOT NULL, customer_id INT UNSIGNED NOT NULL, code VARCHAR(255) NOT NULL, timestamp INT UNSIGNED NOT NULL, date DATE NOT NULL, deadline DATE NOT NULL, acquit_date DATE DEFAULT NULL, state VARCHAR(255) NOT NULL, total_price DOUBLE PRECISION NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_7A2119E39395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bill_line (id INT UNSIGNED AUTO_INCREMENT NOT NULL, bill_id INT UNSIGNED DEFAULT NULL, title VARCHAR(300) NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit_price DOUBLE PRECISION NOT NULL, total_price DOUBLE PRECISION NOT NULL, INDEX IDX_220BDC5C1A8C12F5 (bill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E39395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE bill_line ADD CONSTRAINT FK_220BDC5C1A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bill_line DROP FOREIGN KEY FK_220BDC5C1A8C12F5');
        $this->addSql('DROP TABLE bill');
        $this->addSql('DROP TABLE bill_line');
    }
}
