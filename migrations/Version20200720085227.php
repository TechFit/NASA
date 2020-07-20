<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200720085227
 */
final class Version20200720085227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create asteroid table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE asteroid '
            .'(id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, '
            .'reference CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL,'
            .' speed DOUBLE PRECISION NOT NULL, is_hazardous TINYINT(1) NOT NULL, PRIMARY KEY(id)) '
            .'DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE asteroid');
    }
}
