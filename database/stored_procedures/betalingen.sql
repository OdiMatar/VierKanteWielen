DROP PROCEDURE IF EXISTS sp_get_betalingen_overzicht;
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
END;
