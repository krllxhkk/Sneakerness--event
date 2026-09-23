-- ============================================
-- TESTDATA VERKOPERS
-- ============================================

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
(
    'Sole District',
    0,
    'Sneakers',
    'A',
    'Zaterdag en Zondag',
    NULL,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
),
(
    'Urban Kicks',
    0,
    'Sneakers',
    'B',
    'Zaterdag',
    NULL,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
),
(
    'Street Supply',
    0,
    'Streetwear',
    'A',
    'Zaterdag en Zondag',
    NULL,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
),
(
    'Rare Pairs',
    1,
    'Exclusive Sneakers',
    'C',
    'Zondag',
    NULL,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
),
(
    'Sneaker Vault',
    0,
    'Sneakers',
    'B',
    'Zaterdag en Zondag',
    NULL,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
),
(
    'Lace Lab',
    0,
    'Accessoires',
    'A',
    'Zaterdag',
    NULL,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
);


-- ============================================
-- VERHUURDE STANDS
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

WHERE Naam IN
(
    'Sole District',
    'Street Supply',
    'Rare Pairs'
);


-- ============================================
-- BESCHIKBARE STANDS
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
VALUES
(
    NULL,
    'A',
    150.00,
    0,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
),
(
    NULL,
    'B',
    200.00,
    0,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
),
(
    NULL,
    'C',
    250.00,
    0,
    1,
    'Testdata stands overzicht',
    NOW(),
    NOW()
);