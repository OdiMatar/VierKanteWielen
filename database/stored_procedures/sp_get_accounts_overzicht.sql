USE autorijschool;

-- Stel de delimiter in voor de procedure.
DELIMITER $$

-- Vervang de bestaande overzichtsprocedure.
DROP PROCEDURE IF EXISTS sp_get_accounts_overzicht $$
CREATE PROCEDURE sp_get_accounts_overzicht()
BEGIN
    -- Haal accountgegevens op met betaalinformatie via een LEFT JOIN.
    SELECT
        u.id,
        u.name,
        u.email,
        u.role,
        u.created_at,
        COUNT(b.Id) AS aantal_betalingen,
        COALESCE(SUM(b.Bedrag), 0) AS totaal_betaald,
        COALESCE(SUM(CASE WHEN b.Status = 'Open' THEN b.Bedrag ELSE 0 END), 0) AS openstaand_bedrag
    FROM users AS u
    LEFT JOIN betalingen AS b
        ON b.KlantId = u.id
        AND b.IsActief = 1
    GROUP BY u.id, u.name, u.email, u.role, u.created_at
    ORDER BY u.created_at DESC;
END $$

-- Zet de delimiter terug naar standaard.
DELIMITER ;
