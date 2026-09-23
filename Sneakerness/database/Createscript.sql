-- =========================================================
-- SNEAKERNESS DATABASE
-- =========================================================

DROP DATABASE IF EXISTS `SneakernessDB`;

CREATE DATABASE `SneakernessDB`;

USE `SneakernessDB`;


-- =========================================================
-- 1. ORGANISATOR
-- =========================================================

CREATE TABLE Organisator
(
     Id                 INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam               VARCHAR(100) NOT NULL
    ,Gebruikersnaam     VARCHAR(100) NOT NULL
    ,Wachtwoord         VARCHAR(255) NOT NULL
    ,Isactief           BIT NOT NULL DEFAULT 1
    ,Opmerking          VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt    DATETIME NOT NULL
    ,Datumgewijzigd     DATETIME NOT NULL

    ,CONSTRAINT PK_Organisator_Id
        PRIMARY KEY (Id)
) ENGINE=InnoDB;


-- =========================================================
-- 2. EVENEMENT
-- =========================================================

CREATE TABLE Evenement
(
     Id                         INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam                       VARCHAR(100) NOT NULL
    ,Datum                      DATE NOT NULL
    ,Locatie                    VARCHAR(150) NOT NULL
    ,AantalTicketsPerTijdslot   INT NOT NULL
    ,BeschikbareStands          INT NOT NULL
    ,Isactief                   BIT NOT NULL DEFAULT 1
    ,Opmerking                  VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt            DATETIME NOT NULL
    ,Datumgewijzigd             DATETIME NOT NULL

    ,CONSTRAINT PK_Evenement_Id
        PRIMARY KEY (Id)
) ENGINE=InnoDB;


-- =========================================================
-- 3. PRIJS
-- =========================================================

CREATE TABLE Prijs
(
     Id                 INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Datum              DATE NOT NULL
    ,Tijdslot           TIME NOT NULL
    ,Tarief             DECIMAL(6,2) NOT NULL
    ,Isactief           BIT NOT NULL DEFAULT 1
    ,Opmerking          VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt    DATETIME NOT NULL
    ,Datumgewijzigd     DATETIME NOT NULL

    ,CONSTRAINT PK_Prijs_Id
        PRIMARY KEY (Id)
) ENGINE=InnoDB;


-- =========================================================
-- 4. BEZOEKER
-- =========================================================

CREATE TABLE Bezoeker
(
     Id                 INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam               VARCHAR(100) NOT NULL
    ,`E-mailadres`      VARCHAR(150) NOT NULL
    ,Isactief           BIT NOT NULL DEFAULT 1
    ,Opmerking          VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt    DATETIME NOT NULL
    ,Datumgewijzigd     DATETIME NOT NULL

    ,CONSTRAINT PK_Bezoeker_Id
        PRIMARY KEY (Id)
) ENGINE=InnoDB;


-- =========================================================
-- 5. VERKOPER
-- =========================================================

CREATE TABLE Verkoper
(
     Id                 INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam               VARCHAR(100) NOT NULL
    ,SpecialeStatus     VARCHAR(50) NULL DEFAULT NULL
    ,VerkooptSoort      VARCHAR(100) NOT NULL
    ,StandType          VARCHAR(10) NOT NULL
    ,Dagen              VARCHAR(50) NOT NULL
    ,Logo               VARCHAR(255) NULL DEFAULT NULL
    ,Isactief           BIT NOT NULL DEFAULT 1
    ,Opmerking          VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt    DATETIME NOT NULL
    ,Datumgewijzigd     DATETIME NOT NULL

    ,CONSTRAINT PK_Verkoper_Id
        PRIMARY KEY (Id)
) ENGINE=InnoDB;


-- =========================================================
-- 6. CONTACTPERSOON
-- =========================================================

CREATE TABLE Contactpersoon
(
     Id                 INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam               VARCHAR(100) NOT NULL
    ,Telefoonnummer     VARCHAR(20) NOT NULL
    ,`E-mailadres`      VARCHAR(150) NOT NULL
    ,Isactief           BIT NOT NULL DEFAULT 1
    ,Opmerking          VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt    DATETIME NOT NULL
    ,Datumgewijzigd     DATETIME NOT NULL

    ,CONSTRAINT PK_Contactpersoon_Id
        PRIMARY KEY (Id)
) ENGINE=InnoDB;


-- =========================================================
-- 7. TICKET
-- =========================================================

CREATE TABLE Ticket
(
     Id                 INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,BezoekerId         INT UNSIGNED NOT NULL
    ,EvenementId        INT UNSIGNED NOT NULL
    ,PrijsId            INT UNSIGNED NOT NULL
    ,AantalTickets      INT NOT NULL
    ,Datum              DATE NOT NULL
    ,Isactief           BIT NOT NULL DEFAULT 1
    ,Opmerking          VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt    DATETIME NOT NULL
    ,Datumgewijzigd     DATETIME NOT NULL

    ,CONSTRAINT PK_Ticket_Id
        PRIMARY KEY (Id)

    ,CONSTRAINT FK_Ticket_BezoekerId_Bezoeker_Id
        FOREIGN KEY (BezoekerId)
        REFERENCES Bezoeker(Id)

    ,CONSTRAINT FK_Ticket_EvenementId_Evenement_Id
        FOREIGN KEY (EvenementId)
        REFERENCES Evenement(Id)

    ,CONSTRAINT FK_Ticket_PrijsId_Prijs_Id
        FOREIGN KEY (PrijsId)
        REFERENCES Prijs(Id)
) ENGINE=InnoDB;


-- =========================================================
-- 8. STAND
-- =========================================================

CREATE TABLE Stand
(
     Id                 INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,VerkoperId         INT UNSIGNED NOT NULL
    ,StandType          VARCHAR(3) NOT NULL
    ,Prijs              DECIMAL(8,2) NOT NULL
    ,VerhuurdStatus     BOOLEAN NOT NULL
    ,Isactief           BIT NOT NULL DEFAULT 1
    ,Opmerking          VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt    DATETIME NOT NULL
    ,Datumgewijzigd     DATETIME NOT NULL

    ,CONSTRAINT PK_Stand_Id
        PRIMARY KEY (Id)

    ,CONSTRAINT FK_Stand_VerkoperId_Verkoper_Id
        FOREIGN KEY (VerkoperId)
        REFERENCES Verkoper(Id)
) ENGINE=InnoDB;


-- =========================================================
-- 9. CONTACT PER VERKOPER
-- =========================================================

CREATE TABLE ContactPerVerkoper
(
     Id                     INT UNSIGNED NOT NULL AUTO_INCREMENT
    ,VerkoperId             INT UNSIGNED NOT NULL
    ,ContactpersoonId       INT UNSIGNED NOT NULL
    ,Isactief               BIT NOT NULL DEFAULT 1
    ,Opmerking              VARCHAR(250) NULL DEFAULT NULL
    ,Datumaangemaakt        DATETIME NOT NULL
    ,Datumgewijzigd         DATETIME NOT NULL

    ,CONSTRAINT PK_ContactPerVerkoper_Id
        PRIMARY KEY (Id)

    ,CONSTRAINT FK_ContactPerVerkoper_VerkoperId_Verkoper_Id
        FOREIGN KEY (VerkoperId)
        REFERENCES Verkoper(Id)

    ,CONSTRAINT FK_ContactPerVerkoper_ContactpersoonId_Contactpersoon_Id
        FOREIGN KEY (ContactpersoonId)
        REFERENCES Contactpersoon(Id)
) ENGINE=InnoDB;