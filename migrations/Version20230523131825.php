<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230523131825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE formules (
            id INT AUTO_INCREMENT NOT NULL,
            composition VARCHAR(100) NOT NULL,
            prix DOUBLE PRECISION NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE heure_matins (
            id INT AUTO_INCREMENT NOT NULL,
            h_ouverture TIME NOT NULL,
            h_fermeture TIME NOT NULL,
            is_close TINYINT(1) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE heure_soirs (
            id INT AUTO_INCREMENT NOT NULL,
            h_ouverture TIME NOT NULL,
            h_fermeture TIME NOT NULL,
            is_close TINYINT(1) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE images (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(30) NOT NULL,
            description VARCHAR(100) NOT NULL,
            size DOUBLE PRECISION NOT NULL,
            path VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE jours (
            id INT AUTO_INCREMENT NOT NULL,
            h_matin_id INT NOT NULL,
            h_soir_id INT DEFAULT NULL,
            jour VARCHAR(20) NOT NULL,
            capacite INT DEFAULT NULL,
            INDEX IDX_F0DAEEEDAC169697 (h_matin_id),
            INDEX IDX_F0DAEEED6582C048 (h_soir_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE menus (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(60) NOT NULL,
            description VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE menus_formules (
            menus_id INT NOT NULL,
            formules_id INT NOT NULL,
            INDEX IDX_875B49EB14041B84 (menus_id),
            INDEX IDX_875B49EB168F3793 (formules_id),
            PRIMARY KEY(menus_id, formules_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE plats (
            id INT AUTO_INCREMENT NOT NULL,
            type_id INT NOT NULL,
            name VARCHAR(50) NOT NULL,
            description VARCHAR(255) NOT NULL,
            prix DOUBLE PRECISION NOT NULL,
            INDEX IDX_854A620AC54C8C93 (type_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE reservation (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(150) NOT NULL,
            nmbr_couvert INT NOT NULL,
            allergie VARCHAR(255) DEFAULT NULL,
            heure VARCHAR(255) NOT NULL,
            date DATE NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE type_plats (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(50) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE users (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\',
            password VARCHAR(255) NOT NULL,
            nombre_couvert INT NOT NULL,
            allergie VARCHAR(255) DEFAULT NULL,
            UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL,
            body LONGTEXT NOT NULL,
            headers LONGTEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL,
            available_at DATETIME NOT NULL,
            delivered_at DATETIME DEFAULT NULL,
            INDEX IDX_75EA56E0FB7336F0 (queue_name),
            INDEX IDX_75EA56E0E3BD61CE (available_at),
            INDEX IDX_75EA56E016BA31DB (delivered_at),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE jours ADD CONSTRAINT FK_F0DAEEEDAC169697 FOREIGN KEY (h_matin_id) REFERENCES heure_matins (id)');
        $this->addSql('ALTER TABLE jours ADD CONSTRAINT FK_F0DAEEED6582C048 FOREIGN KEY (h_soir_id) REFERENCES heure_soirs (id)');
        $this->addSql('ALTER TABLE menus_formules ADD CONSTRAINT FK_875B49EB14041B84 FOREIGN KEY (menus_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_formules ADD CONSTRAINT FK_875B49EB168F3793 FOREIGN KEY (formules_id) REFERENCES formules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plats ADD CONSTRAINT FK_854A620AC54C8C93 FOREIGN KEY (type_id) REFERENCES type_plats (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE jours DROP FOREIGN KEY FK_F0DAEEEDAC169697');
        $this->addSql('ALTER TABLE jours DROP FOREIGN KEY FK_F0DAEEED6582C048');
        $this->addSql('ALTER TABLE menus_formules DROP FOREIGN KEY FK_875B49EB14041B84');
        $this->addSql('ALTER TABLE menus_formules DROP FOREIGN KEY FK_875B49EB168F3793');
        $this->addSql('ALTER TABLE plats DROP FOREIGN KEY FK_854A620AC54C8C93');
        $this->addSql('DROP TABLE formules');
        $this->addSql('DROP TABLE heure_matins');
        $this->addSql('DROP TABLE heure_soirs');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE jours');
        $this->addSql('DROP TABLE menus');
        $this->addSql('DROP TABLE menus_formules');
        $this->addSql('DROP TABLE plats');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE type_plats');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

