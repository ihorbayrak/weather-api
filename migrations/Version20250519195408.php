<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519195408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates subscription table with unique constraints on email and token';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE subscription (
            id SERIAL PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            frequency VARCHAR(50) NOT NULL,
            token VARCHAR(255) NOT NULL,
            confirmed BOOLEAN NOT NULL DEFAULT FALSE
        )');

        $this->addSql('CREATE UNIQUE INDEX subscription_email_uidx ON subscription (email)');
        $this->addSql('CREATE UNIQUE INDEX subscription_token_uidx ON subscription (token)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS subscription');
    }
}
