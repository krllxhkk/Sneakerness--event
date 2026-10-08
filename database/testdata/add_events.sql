USE SneakernessDB;

INSERT INTO evenement
(
    Naam,
    Datum,
    Locatie,
    AantalTicketsPerTijdslot,
    BeschikbareStands,
    Isactief,
    Opmerking,
    Datumaangemaakt,
    Datumgewijzigd
)
VALUES
(
    'Sneakerness Rotterdam',
    '2026-11-14',
    'Van Nellefabriek Rotterdam',
    500,
    25,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Sneakerness Amsterdam',
    '2026-12-05',
    'RAI Amsterdam',
    450,
    20,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Sneaker Expo Utrecht',
    '2027-01-16',
    'Jaarbeurs Utrecht',
    350,
    18,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Sneakerness Eindhoven',
    '2027-02-13',
    'Klokgebouw Eindhoven',
    300,
    15,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Sneaker Culture Festival',
    '2027-03-06',
    'Ahoy Rotterdam',
    600,
    30,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Streetwear & Sneakers Expo',
    '2027-04-10',
    'Brabanthallen Den Bosch',
    400,
    22,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Sneakerness The Hague',
    '2027-05-08',
    'Fokker Terminal Den Haag',
    300,
    14,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Urban Sneaker Festival',
    '2027-06-12',
    'Westergas Amsterdam',
    450,
    19,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Sneaker Summer Fest',
    '2027-07-17',
    'Spoorzone Tilburg',
    350,
    16,
    1,
    NULL,
    NOW(),
    NOW()
),
(
    'Sneakerness Closing Event',
    '2027-09-18',
    'Van Nellefabriek Rotterdam',
    550,
    28,
    1,
    NULL,
    NOW(),
    NOW()
);

SELECT * FROM evenement;