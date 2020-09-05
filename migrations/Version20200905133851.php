<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200905133851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lettre_motiv (id INT AUTO_INCREMENT NOT NULL, nom_poste_id INT NOT NULL, file VARCHAR(255) NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B386B5E835DA950F (nom_poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lettre_motiv ADD CONSTRAINT FK_B386B5E835DA950F FOREIGN KEY (nom_poste_id) REFERENCES poste (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lettre_motiv DROP FOREIGN KEY FK_B386B5E835DA950F');
        $this->addSql('DROP TABLE lettre_motiv');
        $this->addSql('DROP TABLE poste');
    }
}
