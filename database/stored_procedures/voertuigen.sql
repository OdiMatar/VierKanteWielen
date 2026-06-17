DROP PROCEDURE IF EXISTS sp_get_instructeurs_in_dienst;
CREATE PROCEDURE sp_get_instructeurs_in_dienst()
BEGIN
    SELECT Id, Voornaam, Tussenvoegsel, Achternaam,
           TRIM(CONCAT(Voornaam, ' ', IFNULL(CONCAT(Tussenvoegsel, ' '), ''), Achternaam)) AS VolledigeNaam,
           Mobiel, DatumInDienst, AantalSterren
    FROM instructeurs
    WHERE IsActief = 1
    ORDER BY CHAR_LENGTH(AantalSterren) DESC, Achternaam ASC, Voornaam ASC;
END;

DROP PROCEDURE IF EXISTS sp_get_voertuigen_bij_instructeur;
CREATE PROCEDURE sp_get_voertuigen_bij_instructeur(IN p_InstructeurId BIGINT UNSIGNED)
BEGIN
    SELECT v.Id, v.Kenteken, v.Type, v.Bouwjaar, v.Brandstof,
           tv.TypeVoertuig, tv.Rijbewijscategorie, vi.DatumToekenning
    FROM voertuigen v
    INNER JOIN voertuig_instructeur vi ON vi.VoertuigId = v.Id
    INNER JOIN type_voertuigen tv ON tv.Id = v.TypeVoertuigId
    WHERE vi.InstructeurId = p_InstructeurId
      AND v.IsActief = 1
      AND vi.IsActief = 1
    ORDER BY tv.Rijbewijscategorie ASC, v.Type ASC;
END;

DROP PROCEDURE IF EXISTS sp_get_beschikbare_voertuigen;
CREATE PROCEDURE sp_get_beschikbare_voertuigen()
BEGIN
    SELECT v.Id, v.Kenteken, v.Type, v.Bouwjaar, v.Brandstof,
           tv.TypeVoertuig, tv.Rijbewijscategorie
    FROM voertuigen v
    INNER JOIN type_voertuigen tv ON tv.Id = v.TypeVoertuigId
    LEFT JOIN voertuig_instructeur vi ON vi.VoertuigId = v.Id
    WHERE vi.Id IS NULL
      AND v.IsActief = 1
    ORDER BY tv.Rijbewijscategorie ASC, v.Type ASC;
END;

DROP PROCEDURE IF EXISTS sp_get_voertuig_edit;
CREATE PROCEDURE sp_get_voertuig_edit(IN p_VoertuigId BIGINT UNSIGNED)
BEGIN
    SELECT v.Id, v.Kenteken, v.Type, v.Bouwjaar, v.Brandstof, v.TypeVoertuigId, vi.InstructeurId
    FROM voertuigen v
    LEFT JOIN voertuig_instructeur vi ON vi.VoertuigId = v.Id
    WHERE v.Id = p_VoertuigId;
END;

DROP PROCEDURE IF EXISTS sp_update_voertuig;
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
END;
