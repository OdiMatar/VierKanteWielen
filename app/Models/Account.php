<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Throwable;

class Account extends Model
{
    private const ACCOUNT_LOG_FILE = 'accountlog.log';

    protected $table = 'users';

    public static function allViaStoredProcedure(): Collection
    {
        try {
            $accounts = self::accountsOverzicht();

            self::accountLogger()->info('Accounts succesvol opgehaald.', [
                'count' => $accounts->count(),
            ]);

            return $accounts;
        } catch (Throwable $exception) {
            // Bij fouten loggen en een lege collectie teruggeven.
            self::accountLogger()->error('Fout bij ophalen van accounts.', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return collect();
        }
    }

    private static function accountsOverzicht(): Collection
    {
        if (DB::getDriverName() !== 'mysql') {
            return self::query()
                ->select(['id', 'name', 'email', 'role', 'created_at'])
                ->orderByDesc('created_at')
                ->get();
        }

        return collect(DB::select('CALL sp_get_accounts_overzicht()'));
    }

    private static function accountLogger(): LoggerInterface
    {
        return Log::build([
            'driver' => 'single',
            'path' => database_path(self::ACCOUNT_LOG_FILE),
        ]);
    }
}
