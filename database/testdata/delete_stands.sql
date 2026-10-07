USE SneakernessDB;


-- =========================================================
-- DELETE TESTDATA STANDS
-- Verwijdert alleen de testdata van het Stands-overzicht
-- =========================================================

DELETE FROM Stand
WHERE Id > 0
AND Opmerking IN
(
    'Testdata verhuurde stand',
    'Testdata beschikbare stand'
);


-- =========================================================
-- CONTROLEREN
-- =========================================================

SELECT * FROM Stand;