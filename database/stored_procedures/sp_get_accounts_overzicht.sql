USE autorijschool;

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_get_accounts_overzicht $$
CREATE PROCEDURE sp_get_accounts_overzicht()
BEGIN
    SELECT id, name, email, role, created_at
    FROM users
    ORDER BY created_at DESC;
END $$

DELIMITER ;
