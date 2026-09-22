-- Step: 01
-- Goal: Create a new database Sneakerness
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Check if the database exists
DROP DATABASE IF EXISTS `SneakernessDB`;

-- Create a new Database
CREATE DATABASE IF NOT EXISTS `SneakernessDB`;

-- Use database SneakernessDB
Use `SneakernessDB`;


-- Step: 02
-- Goal: Create a new table Organisator
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Organisator
DROP TABLE IF EXISTS Organisator;

CREATE TABLE IF NOT EXISTS Organisator
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(100)                    NOT NULL
   ,Gebruikersnaam      VARCHAR(100)                    NOT NULL
   ,Wachtwoord          VARCHAR(255)                    NOT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Organisator_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


-- Step: 03
-- Goal: Create a new table Evenement
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Evenement
DROP TABLE IF EXISTS Evenement;

CREATE TABLE IF NOT EXISTS Evenement
(
    Id                      INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                    VARCHAR(100)                    NOT NULL
   ,Datum                   DATE                            NOT NULL
   ,Locatie                 VARCHAR(150)                    NOT NULL
   ,AantalTicketsPerTijdslot INT                            NOT NULL
   ,BeschikbareStands       INT                             NOT NULL
   ,Isactief                BIT                             NOT NULL    DEFAULT 1
   ,Opmerking               VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt         DATETIME                        NOT NULL
   ,Datumgewijzigd          DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Evenement_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


-- Step: 04
-- Goal: Create a new table Prijs
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Prijs
DROP TABLE IF EXISTS Prijs;

CREATE TABLE IF NOT EXISTS Prijs
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Datum               DATE                            NOT NULL
   ,Tijdslot            TIME                            NOT NULL
   ,Tarief              DECIMAL(6,2)                    NOT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Prijs_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


-- Step: 05
-- Goal: Create a new table Bezoeker
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Bezoeker
DROP TABLE IF EXISTS Bezoeker;

CREATE TABLE IF NOT EXISTS Bezoeker
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(100)                    NOT NULL
   ,`E-mailadres`       VARCHAR(150)                    NOT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Bezoeker_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


-- Step: 06
-- Goal: Create a new table Verkoper
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Verkoper
DROP TABLE IF EXISTS Verkoper;

CREATE TABLE IF NOT EXISTS Verkoper
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(100)                    NOT NULL
   ,SpecialeStatus      VARCHAR(50)                         NULL    DEFAULT NULL
   ,VerkooptSoort       VARCHAR(100)                    NOT NULL
   ,StandType           VARCHAR(10)                     NOT NULL
   ,Dagen               VARCHAR(50)                     NOT NULL
   ,Logo                VARCHAR(255)                        NULL    DEFAULT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Verkoper_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


-- Step: 07
-- Goal: Create a new table Contactpersoon
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Contactpersoon
DROP TABLE IF EXISTS Contactpersoon;

CREATE TABLE IF NOT EXISTS Contactpersoon
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,Naam                VARCHAR(100)                    NOT NULL
   ,Telefoonnummer      VARCHAR(20)                     NOT NULL
   ,`E-mailadres`       VARCHAR(150)                    NOT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Contactpersoon_Id   PRIMARY KEY CLUSTERED(Id)
) ENGINE=InnoDB;


-- Step: 08
-- Goal: Create a new table Ticket
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Ticket
DROP TABLE IF EXISTS Ticket;

CREATE TABLE IF NOT EXISTS Ticket
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,BezoekerId          INT             UNSIGNED        NOT NULL
   ,EvenementId         INT             UNSIGNED        NOT NULL
   ,PrijsId             INT             UNSIGNED        NOT NULL
   ,AantalTickets       INT                             NOT NULL
   ,Datum               DATE                            NOT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Ticket_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_Ticket_BezoekerId_Bezoeker_Id FOREIGN KEY (BezoekerId) REFERENCES Bezoeker(Id)
   ,CONSTRAINT      FK_Ticket_EvenementId_Evenement_Id FOREIGN KEY (EvenementId) REFERENCES Evenement(Id)
   ,CONSTRAINT      FK_Ticket_PrijsId_Prijs_Id FOREIGN KEY (PrijsId) REFERENCES Prijs(Id)
) ENGINE=InnoDB;


-- Step: 09
-- Goal: Create a new table Stand
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table Stand
DROP TABLE IF EXISTS Stand;

CREATE TABLE IF NOT EXISTS Stand
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,VerkoperId          INT             UNSIGNED        NOT NULL
   ,StandType           VARCHAR(3)                      NOT NULL
   ,Prijs               DECIMAL(8,2)                    NOT NULL
   ,VerhuurdStatus      BOOLEAN                         NOT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_Stand_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_Stand_VerkoperId_Verkoper_Id FOREIGN KEY (VerkoperId) REFERENCES Verkoper(Id)
) ENGINE=InnoDB;


-- Step: 10
-- Goal: Create a new table ContactPerVerkoper
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            16-09-2026      Ali                         New
-- **********************************************************************************/

-- Drop table ContactPerVerkoper
DROP TABLE IF EXISTS ContactPerVerkoper;

CREATE TABLE IF NOT EXISTS ContactPerVerkoper
(
    Id                  INT             UNSIGNED        NOT NULL    AUTO_INCREMENT
   ,VerkoperId          INT             UNSIGNED        NOT NULL
   ,ContactpersoonId    INT             UNSIGNED        NOT NULL
   ,Isactief            BIT                             NOT NULL    DEFAULT 1
   ,Opmerking           VARCHAR(250)                        NULL    DEFAULT NULL
   ,Datumaangemaakt     DATETIME                        NOT NULL
   ,Datumgewijzigd      DATETIME                        NOT NULL

   ,CONSTRAINT      PK_ContactPerVerkoper_Id   PRIMARY KEY CLUSTERED(Id)
   ,CONSTRAINT      FK_ContactPerVerkoper_VerkoperId_Verkoper_Id FOREIGN KEY (VerkoperId) REFERENCES Verkoper(Id)
   ,CONSTRAINT      FK_ContactPerVerkoper_ContactpersoonId_Contactpersoon_Id FOREIGN KEY (ContactpersoonId) REFERENCES Contactpersoon(Id)
) ENGINE=InnoDB;