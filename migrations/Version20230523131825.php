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
        
        //insert into formules
        $this->addSql('INSERT INTO formules (composition, prix) VALUES 
            ("Entrée Plat Dessert", 15.99),
            ("Entrée Plat", 10.99),
            ("Plat Dessert", 10.99)
        ');
        //insert into heure matin
        $this->addSql('INSERT INTO heure_matins (h_ouverture, h_fermeture, is_close) VALUES
            ("08:00:00", "12:00:00", 0),
            ("08:00:00", "12:00:00", 0),
            ("08:00:00", "12:00:00", 0),
            ("08:00:00", "12:00:00", 0),
            ("08:00:00", "12:00:00", 0),
            ("08:00:00", "12:00:00", 0),
            ("08:00:00", "12:00:00", 0)
        ');
        //insert into heure soir
        $this->addSql('INSERT INTO heure_soirs (h_ouverture, h_fermeture, is_close) VALUES
            ("18:00:00", "22:00:00", 0),
            ("18:00:00", "22:00:00", 0),
            ("18:00:00", "22:00:00", 0),
            ("18:00:00", "22:00:00", 0),
            ("18:00:00", "22:00:00", 0),
            ("18:00:00", "22:00:00", 0),
            ("18:00:00", "22:00:00", 0)
        ');

        // Insert into jours
        $this->addSql('INSERT INTO jours (h_matin_id, h_soir_id, jour, capacite) VALUES 
            (1, 1, "Lundi", 50),
            (2, 2, "Mardi", 60),
            (3, 3, "Mercredi", 40),
            (4, 4, "Jeudi", 40),
            (5, 5, "Vendredi", 40),
            (6, 6, "Samedi", 40),
            (7, 7, "Dimanche", 40)
        ');

        //Insert into menus
        $this->addSql('INSERT INTO menus (name, description) VALUES 
            ("Menus Catalan", "du lundi au mercredi soir"),
            ("Menus du bouchée", "du lundi au mercredi soir"),
            ("Menu 3", "Description du Menu 3")
        ');

        //insert into menu_formule
        $this->addSql('INSERT INTO menus_formules (menus_id, formules_id) VALUES 
            (1, 1),
            (1, 2),
            (2, 2),
            (3, 3)
        ');

        //insert into typePlat
        $this->addSql('INSERT INTO type_plats (name) VALUES 
            ("Plat"),
            ("Entrée"),
            ("Dessert")
        ');

        //insert into plat
        $this->addSql('INSERT INTO plats (type_id, name, description, prix) VALUES 
            (1, "Tartiflette", "Plat traditionnel savoyard à base de pommes de terre, de lardons et de reblochon", 12.99),
            (1, "Raclette", "Plat convivial à base de fromage fondu et de pommes de terre", 14.99),
            (2, "Fondue Savoyarde", "Fondue au fromage emblématique de la région", 15.99),
            (2, "Diots au Vin Blanc", "Saucisses de porc aux saveurs de vin blanc et d\'échalotes", 13.99)
        ');

        //insert into reservation
        $this->addSql('INSERT INTO reservation (email, nmbr_couvert, allergie, heure, date) VALUES 
            ("client1@example.com", 2, "Aucune", "19:30", "2023-09-25"),
            ("client2@example.com", 4, "Gluten", "20:00", "2023-09-26"),
            ("client3@example.com", 6, "Lactose", "19:00", "2023-09-27")
        ');

        //insert into User
        $this->addSql('INSERT INTO users (email, roles, password, nombre_couvert, allergie) VALUES 
            ("admin@example.com", \'["ROLE_USER","ROLE_ADMIN"]\', "admin134", 2, "Aucune"),
            ("utilisateur2@example.com", \'["ROLE_USER"]\', "motdepasse2", 4, "Gluten"),
            ("utilisateur3@example.com", \'["ROLE_USER"]\', "motdepasse3", 6, "Lactose")
        ');

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

