<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared(<<<'SQL'
DROP PROCEDURE IF EXISTS sp_create_account;
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

        SELECT
            1 AS success,
            'account is toegevoegd' AS message,
            u.id AS account_id,
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
        WHERE u.id = LAST_INSERT_ID()
        GROUP BY u.id, u.name, u.email, u.role, u.created_at;
    END IF;
END
SQL);
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared(<<<'SQL'
DROP PROCEDURE IF EXISTS sp_create_account;
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
END
SQL);
    }
};
