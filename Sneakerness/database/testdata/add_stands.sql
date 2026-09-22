INSERT INTO Verkoper
(
    Naam,
    SpecialeStatus,
    VerkooptSoort,
    StandType,
    Dagen,
    Logo,
    Isactief,
    Opmerking,
    Datumaangemaakt,
    Datumgewijzigd
)
VALUES
('Sole District', 0, 'Sneakers', 'A', 'Zaterdag en Zondag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Urban Kicks', 0, 'Sneakers', 'B', 'Zaterdag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Street Supply', 0, 'Streetwear', 'A', 'Zaterdag en Zondag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Rare Pairs', 1, 'Exclusive Sneakers', 'C', 'Zondag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Sneaker Vault', 0, 'Sneakers', 'B', 'Zaterdag en Zondag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Lace Lab', 0, 'Accessoires', 'A', 'Zaterdag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Retro Soles', 0, 'Vintage Sneakers', 'C', 'Zondag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Fresh Fits', 0, 'Streetwear', 'B', 'Zaterdag en Zondag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Kick Culture', 1, 'Sneakers', 'A', 'Zaterdag en Zondag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW()),
('Custom Kicks', 0, 'Custom Sneakers', 'C', 'Zaterdag', NULL, 1, 'Testdata stands overzicht', NOW(), NOW());


-- ============================================
-- TESTDATA STANDS
-- ============================================

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
        WHEN StandType = 'A' THEN 150.00
        WHEN StandType = 'B' THEN 200.00
        WHEN StandType = 'C' THEN 250.00
    END,
    1,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
FROM Verkoper
WHERE Opmerking = 'Testdata stands overzicht';