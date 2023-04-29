<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230429155139 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency (id INT NOT NULL, currency VARCHAR(5) NOT NULL, date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        // password: password
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES(1, \'permitted@mail.com\', \'$2a$12$HHnyjJi1grXlLgSSYr5UEOhHUs9s5cxOGG97bVXW9BNrWS7WswUS6\', \'["ROLE_API"]\')');
        $this->addSql('INSERT INTO "user" (id, email, password, roles) VALUES(2, \'forbidden@mail.com\', \'$2a$12$HHnyjJi1grXlLgSSYr5UEOhHUs9s5cxOGG97bVXW9BNrWS7WswUS6\', \'[]\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE currency_id_seq CASCADE');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "user"');
    }
}
