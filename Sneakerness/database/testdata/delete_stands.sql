USE SneakernessDB;

SET SQL_SAFE_UPDATES = 0;

-- Eerst de stands verwijderen vanwege de foreign key naar Verkoper
DELETE FROM Stand
WHERE Opmerking = 'Testdata stands overzicht';

-- Daarna alleen onze testverkopers verwijderen
DELETE FROM Verkoper
WHERE Opmerking = 'Testdata stands overzicht';

SET SQL_SAFE_UPDATES = 1;

SELECT * FROM Stand;