DROP DATABASE IF EXISTS autorijschool;
CREATE DATABASE autorijschool CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE autorijschool;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(20) NOT NULL DEFAULT 'instructeur',
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

CREATE TABLE instructeurs (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Voornaam VARCHAR(80) NOT NULL,
    Tussenvoegsel VARCHAR(20) NULL,
    Achternaam VARCHAR(80) NOT NULL,
    Mobiel VARCHAR(15) NOT NULL,
    DatumInDienst DATE NOT NULL,
    AantalSterren VARCHAR(5) NOT NULL,
    IsActief TINYINT(1) NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE type_voertuigen (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TypeVoertuig VARCHAR(80) NOT NULL,
    Rijbewijscategorie VARCHAR(5) NOT NULL,
    IsActief TINYINT(1) NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE voertuigen (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Kenteken VARCHAR(10) NOT NULL UNIQUE,
    Type VARCHAR(80) NOT NULL,
    Bouwjaar DATE NOT NULL,
    Brandstof VARCHAR(20) NOT NULL,
    TypeVoertuigId BIGINT UNSIGNED NOT NULL,
    IsActief TINYINT(1) NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_voertuigen_type_voertuigen FOREIGN KEY (TypeVoertuigId) REFERENCES type_voertuigen(Id)
);

CREATE TABLE voertuig_instructeur (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    VoertuigId BIGINT UNSIGNED NOT NULL UNIQUE,
    InstructeurId BIGINT UNSIGNED NOT NULL,
    DatumToekenning DATE NOT NULL,
    IsActief TINYINT(1) NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_voertuig_instructeur_voertuigen FOREIGN KEY (VoertuigId) REFERENCES voertuigen(Id) ON DELETE CASCADE,
    CONSTRAINT fk_voertuig_instructeur_instructeurs FOREIGN KEY (InstructeurId) REFERENCES instructeurs(Id) ON DELETE CASCADE
);

CREATE TABLE betalingen (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    KlantId BIGINT UNSIGNED NOT NULL,
    Bedrag DECIMAL(8, 2) NOT NULL,
    Betaalmethode VARCHAR(40) NOT NULL,
    Status VARCHAR(30) NOT NULL,
    IsActief TINYINT(1) NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_betalingen_users FOREIGN KEY (KlantId) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, role, password) VALUES
('Owner Autorijschool', 'owner@autorijschool.test', 'owner', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq'),
('Admin Autorijschool', 'admin@autorijschool.test', 'admin', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq'),
('Instructeur Demo', 'instructeur@autorijschool.test', 'instructeur', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq'),
('Sanne Jansen', 'sanne.jansen@autorijschool.test', 'leerling', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq'),
('Yassin El Amrani', 'yassin.elamrani@autorijschool.test', 'leerling', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq'),
('Noor de Vries', 'noor.devries@autorijschool.test', 'leerling', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq'),
('Milan Bakker', 'milan.bakker@autorijschool.test', 'leerling', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq'),
('Emma Vermeer', 'emma.vermeer@autorijschool.test', 'leerling', '$2y$10$NnVI/fhYpY65RhnNL5O18ustnbl7h.vHcd2GK8OOC64bNxSd7EJSq');

INSERT INTO type_voertuigen (Id, TypeVoertuig, Rijbewijscategorie) VALUES
(1, 'Personenauto', 'B'),
(2, 'Vrachtwagen', 'C'),
(3, 'Bus', 'D'),
(4, 'Bromfiets', 'AM');

INSERT INTO voertuigen (Id, Kenteken, Type, Bouwjaar, Brandstof, TypeVoertuigId) VALUES
(1, 'AU-67-IO', 'Golf', '2017-06-12', 'Diesel', 1),
(2, 'TR-24-OP', 'DAF', '2019-05-23', 'Diesel', 2),
(3, 'TH-78-KL', 'Mercedes', '2023-01-01', 'Benzine', 1),
(4, '90-KL-TR', 'Fiat 500', '2021-09-12', 'Benzine', 1),
(5, '34-TK-LP', 'Scania', '2015-03-13', 'Diesel', 2),
(6, 'YY-OP-78', 'BMW M5', '2022-05-13', 'Diesel', 1),
(7, 'UU-HH-JK', 'M.A.N', '2017-12-03', 'Diesel', 2),
(8, 'ST-FZ-28', 'Citroen', '2018-01-20', 'Elektrisch', 1),
(9, '123-FR-T', 'Piaggio ZIP', '2021-02-01', 'Benzine', 4),
(10, 'DRS-52-P', 'Vespa', '2022-03-21', 'Benzine', 4),
(11, 'STP-12-U', 'Kymco', '2022-07-02', 'Benzine', 4),
(12, '45-SD-23', 'Renault', '2023-01-01', 'Diesel', 3);

INSERT INTO instructeurs (Id, Voornaam, Tussenvoegsel, Achternaam, Mobiel, DatumInDienst, AantalSterren) VALUES
(1, 'Li', NULL, 'Zhan', '06-28493827', '2015-04-17', '***'),
(2, 'Leroy', NULL, 'Boerhaven', '06-39398734', '2018-06-25', '*'),
(3, 'Yoeri', 'Van', 'Veen', '06-24383291', '2010-05-12', '***'),
(4, 'Bert', 'Van', 'Sali', '06-48293823', '2023-01-10', '****'),
(5, 'Mohammed', 'El', 'Yassidi', '06-34291234', '2010-06-14', '*****');

INSERT INTO voertuig_instructeur (Id, VoertuigId, InstructeurId, DatumToekenning) VALUES
(1, 1, 5, '2017-06-18'),
(2, 3, 1, '2021-09-26'),
(3, 9, 1, '2021-09-27'),
(4, 4, 4, '2022-08-01'),
(5, 5, 1, '2019-08-30'),
(6, 10, 5, '2020-02-02'),
(7, 2, 5, '2020-03-12');

INSERT INTO betalingen (KlantId, Bedrag, Betaalmethode, Status, Opmerking) VALUES
((SELECT id FROM users WHERE email = 'sanne.jansen@autorijschool.test'), 549.99, 'iDEAL', 'Betaald', 'Aanbetaling basispakket'),
((SELECT id FROM users WHERE email = 'yassin.elamrani@autorijschool.test'), 999.99, 'Bankoverschrijving', 'Open', 'Termijnbetaling standaard pakket'),
((SELECT id FROM users WHERE email = 'noor.devries@autorijschool.test'), 59.99, 'Pin', 'Betaald', 'Losse extra rijles'),
((SELECT id FROM users WHERE email = 'milan.bakker@autorijschool.test'), 1699.99, 'iDEAL', 'Betaald', 'Premium pakket volledig betaald'),
((SELECT id FROM users WHERE email = 'emma.vermeer@autorijschool.test'), 110.00, 'Contant', 'Open', 'Examentraining en praktijkexamen');

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_get_accounts_overzicht $$
CREATE PROCEDURE sp_get_accounts_overzicht()
BEGIN
    SELECT id, name, email, role, created_at
    FROM users
    ORDER BY created_at DESC;
END $$

DROP PROCEDURE IF EXISTS sp_create_account $$
CREATE PROCEDURE sp_create_account(
    IN p_name VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_role VARCHAR(20),
    IN p_password VARCHAR(255)
)
BEGIN
    IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
        SELECT 0 AS success, 'deze email is al in gebruik' AS message, NULL AS account_id;
    ELSE
        INSERT INTO users (name, email, role, password, created_at, updated_at)
        VALUES (p_name, p_email, p_role, p_password, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

        SELECT 1 AS success, 'account is toegevoegd' AS message, LAST_INSERT_ID() AS account_id;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_get_instructeurs_in_dienst $$
CREATE PROCEDURE sp_get_instructeurs_in_dienst()
BEGIN
    SELECT
        Id,
        Voornaam,
        Tussenvoegsel,
        Achternaam,
        TRIM(CONCAT(Voornaam, ' ', IFNULL(CONCAT(Tussenvoegsel, ' '), ''), Achternaam)) AS VolledigeNaam,
        Mobiel,
        DatumInDienst,
        AantalSterren
    FROM instructeurs
    WHERE IsActief = 1
    ORDER BY CHAR_LENGTH(AantalSterren) DESC, Achternaam ASC, Voornaam ASC;
END $$

DROP PROCEDURE IF EXISTS sp_get_betalingen_overzicht $$
CREATE PROCEDURE sp_get_betalingen_overzicht(
    IN p_Zoekterm VARCHAR(255),
    IN p_Status VARCHAR(30),
    IN p_Betaalmethode VARCHAR(40)
)
BEGIN
    SELECT
        b.Id,
        u.name AS KlantNaam,
        u.email AS KlantEmail,
        b.Bedrag,
        b.Betaalmethode,
        b.Status,
        b.Opmerking,
        b.DatumAangemaakt
    FROM betalingen b
    INNER JOIN users u ON u.id = b.KlantId
    WHERE b.IsActief = 1
      AND (
          p_Zoekterm = ''
          OR u.name LIKE CONCAT('%', p_Zoekterm, '%')
          OR u.email LIKE CONCAT('%', p_Zoekterm, '%')
          OR b.Betaalmethode LIKE CONCAT('%', p_Zoekterm, '%')
          OR b.Status LIKE CONCAT('%', p_Zoekterm, '%')
          OR b.Opmerking LIKE CONCAT('%', p_Zoekterm, '%')
      )
      AND (p_Status = '' OR b.Status = p_Status)
      AND (p_Betaalmethode = '' OR b.Betaalmethode = p_Betaalmethode)
    ORDER BY b.DatumAangemaakt DESC, b.Id DESC;
END $$

DROP PROCEDURE IF EXISTS sp_get_voertuigen_bij_instructeur $$
CREATE PROCEDURE sp_get_voertuigen_bij_instructeur(IN p_InstructeurId BIGINT UNSIGNED)
BEGIN
    SELECT
        v.Id,
        v.Kenteken,
        v.Type,
        v.Bouwjaar,
        v.Brandstof,
        tv.TypeVoertuig,
        tv.Rijbewijscategorie,
        vi.DatumToekenning
    FROM voertuigen v
    INNER JOIN voertuig_instructeur vi ON vi.VoertuigId = v.Id
    INNER JOIN type_voertuigen tv ON tv.Id = v.TypeVoertuigId
    WHERE vi.InstructeurId = p_InstructeurId
      AND v.IsActief = 1
      AND vi.IsActief = 1
    ORDER BY tv.Rijbewijscategorie ASC, v.Type ASC;
END $$

DROP PROCEDURE IF EXISTS sp_get_beschikbare_voertuigen $$
CREATE PROCEDURE sp_get_beschikbare_voertuigen()
BEGIN
    SELECT
        v.Id,
        v.Kenteken,
        v.Type,
        v.Bouwjaar,
        v.Brandstof,
        tv.TypeVoertuig,
        tv.Rijbewijscategorie
    FROM voertuigen v
    INNER JOIN type_voertuigen tv ON tv.Id = v.TypeVoertuigId
    LEFT JOIN voertuig_instructeur vi ON vi.VoertuigId = v.Id
    WHERE vi.Id IS NULL
      AND v.IsActief = 1
    ORDER BY tv.Rijbewijscategorie ASC, v.Type ASC;
END $$

DROP PROCEDURE IF EXISTS sp_get_voertuig_edit $$
CREATE PROCEDURE sp_get_voertuig_edit(IN p_VoertuigId BIGINT UNSIGNED)
BEGIN
    SELECT
        v.Id,
        v.Kenteken,
        v.Type,
        v.Bouwjaar,
        v.Brandstof,
        v.TypeVoertuigId,
        vi.InstructeurId
    FROM voertuigen v
    LEFT JOIN voertuig_instructeur vi ON vi.VoertuigId = v.Id
    WHERE v.Id = p_VoertuigId;
END $$

DROP PROCEDURE IF EXISTS sp_update_voertuig $$
CREATE PROCEDURE sp_update_voertuig(
    IN p_OrigineleInstructeurId BIGINT UNSIGNED,
    IN p_VoertuigId BIGINT UNSIGNED,
    IN p_Kenteken VARCHAR(10),
    IN p_Type VARCHAR(80),
    IN p_Brandstof VARCHAR(20),
    IN p_TypeVoertuigId BIGINT UNSIGNED,
    IN p_InstructeurId BIGINT UNSIGNED
)
BEGIN
    UPDATE voertuigen
    SET Kenteken = p_Kenteken,
        Type = p_Type,
        Brandstof = p_Brandstof,
        TypeVoertuigId = p_TypeVoertuigId,
        DatumGewijzigd = CURRENT_TIMESTAMP
    WHERE Id = p_VoertuigId;

    IF EXISTS (SELECT 1 FROM voertuig_instructeur WHERE VoertuigId = p_VoertuigId) THEN
        UPDATE voertuig_instructeur
        SET InstructeurId = p_InstructeurId,
            IsActief = 1,
            DatumGewijzigd = CURRENT_TIMESTAMP
        WHERE VoertuigId = p_VoertuigId;
    ELSE
        INSERT INTO voertuig_instructeur (VoertuigId, InstructeurId, DatumToekenning, IsActief)
        VALUES (p_VoertuigId, p_InstructeurId, CURRENT_DATE, 1);
    END IF;
END $$

DELIMITER ;
