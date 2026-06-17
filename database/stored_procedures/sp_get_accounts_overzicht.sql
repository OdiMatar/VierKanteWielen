USE autorijschool;

-- Stel de delimiter in voor de procedure.
DELIMITER $$

-- Vervang de bestaande overzichtsprocedure.
DROP PROCEDURE IF EXISTS sp_get_accounts_overzicht $$
CREATE PROCEDURE sp_get_accounts_overzicht()
BEGIN
    -- Haal de belangrijkste accountvelden op.
    SELECT id, name, email, role, created_at
    FROM users
    ORDER BY created_at DESC;
END $$

-- Zet de delimiter terug naar standaard.
DELIMITER ;
