USE autorijschool;

-- Stel de delimiter in voor de procedure.
DELIMITER $$

-- Vervang de bestaande aanmaakprocedure.
DROP PROCEDURE IF EXISTS sp_create_account $$
CREATE PROCEDURE sp_create_account(
    IN p_name VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_role VARCHAR(20),
    IN p_password VARCHAR(255)
)
BEGIN
    -- Controleer eerst of het e-mailadres al bestaat.
    IF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
        SELECT 0 AS success, 'deze email is al in gebruik' AS message, NULL AS account_id;
    ELSE
        -- Voeg het nieuwe account toe.
        INSERT INTO users (name, email, role, password, created_at, updated_at)
        VALUES (p_name, p_email, p_role, p_password, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

        SELECT 1 AS success, 'account is toegevoegd' AS message, LAST_INSERT_ID() AS account_id;
    END IF;
END $$

-- Zet de delimiter terug naar standaard.
DELIMITER ;
