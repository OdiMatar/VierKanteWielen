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
DROP PROCEDURE IF EXISTS sp_get_accounts_overzicht;
CREATE PROCEDURE sp_get_accounts_overzicht()
BEGIN
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
END
SQL);
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared(<<<'SQL'
DROP PROCEDURE IF EXISTS sp_get_accounts_overzicht;
CREATE PROCEDURE sp_get_accounts_overzicht()
BEGIN
    SELECT id, name, email, role, created_at
    FROM users
    ORDER BY created_at DESC;
END
SQL);
    }
};
