USE SneakernessDB;


-- =========================================================
-- TESTDATA STANDS
-- User Story: Stands overzicht
-- =========================================================


-- =========================================================
-- 1. OUDE STAND TESTDATA VERWIJDEREN
-- =========================================================

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE Stand;

SET FOREIGN_KEY_CHECKS = 1;


-- =========================================================
-- 2. VERHUURDE STANDS
-- De verkopers bestaan al in de Verkoper-tabel.
-- Daarom worden de VerkoperIds automatisch opgehaald.
-- =========================================================

INSERT INTO Stand
(
    VerkoperId,
    StandType,
    Prijs,
    VerhuurdStatus,
    Isactief,
    Opmerking,
    Datumaangemaakt,
    Datumgewijzigd
)
SELECT
    Id,
    StandType,

    CASE
        WHEN StandType = 'Klein' THEN 100.00
        WHEN StandType = 'Middel' THEN 150.00
        WHEN StandType = 'Groot' THEN 200.00
    END,

    1,
    1,
    'Testdata verhuurde stand',
    NOW(),
    NOW()

FROM Verkoper

WHERE Naam IN
(
    'Sneaker District',
    'Urban Kicks',
    'Custom Creps',
    'Vintage Sole',
    'Sole Supply',
    'Street Culture'
);


-- =========================================================
-- 3. BESCHIKBARE STANDS
-- VerkoperId is NULL omdat deze stands nog niet verhuurd zijn.
-- =========================================================

INSERT INTO Stand
(
    VerkoperId,
    StandType,
    Prijs,
    VerhuurdStatus,
    Isactief,
    Opmerking,
    Datumaangemaakt,
    Datumgewijzigd
)
VALUES

(
    NULL,
    'Klein',
    100.00,
    0,
    1,
    'Testdata beschikbare stand',
    NOW(),
    NOW()
),

(
    NULL,
    'Middel',
    150.00,
    0,
    1,
    'Testdata beschikbare stand',
    NOW(),
    NOW()
),

(
    NULL,
    'Groot',
    200.00,
    0,
    1,
    'Testdata beschikbare stand',
    NOW(),
    NOW()
),

(
    NULL,
    'Middel',
    150.00,
    0,
    1,
    'Testdata beschikbare stand',
    NOW(),
    NOW()
);


-- =========================================================
-- 4. TESTDATA CONTROLEREN
-- =========================================================

SELECT
    s.Id AS StandId,
    s.StandType,
    s.Prijs,

    CASE
        WHEN s.VerhuurdStatus = 1 THEN 'Verhuurd'
        ELSE 'Beschikbaar'
    END AS Status,

    s.VerkoperId,
    v.Naam AS Verkoper,
    v.VerkooptSoort AS Categorie

FROM Stand s

LEFT JOIN Verkoper v
    ON v.Id = s.VerkoperId

ORDER BY
    s.VerhuurdStatus ASC,
    s.Id ASC;