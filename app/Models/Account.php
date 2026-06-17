<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Throwable;

class Account extends Model
{
    private const ACCOUNT_LOG_FILE = 'accountlog.log';

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'role', 'password'];

    public static function allViaStoredProcedure(): Collection
    {
        try {
            $accounts = DB::getDriverName() === 'mysql'
                ? collect(DB::select('CALL sp_get_accounts_overzicht()'))
                : self::query()
                    ->select(['id', 'name', 'email', 'role', 'created_at'])
                    ->orderByDesc('created_at')
                    ->get();

            // Log een simpele succesmelding met het aantal records.
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

    /**
     * @param array{name: string, email: string, role: string, password: string} $data
     * @return array{success: bool, message: string, account_id?: int|null}
     */
    public static function createViaStoredProcedure(array $data): array
    {
        try {
            $data['password'] = Hash::make($data['password']);

            if (DB::getDriverName() === 'mysql') {
                $result = DB::selectOne('CALL sp_create_account(?, ?, ?, ?)', [
                    $data['name'],
                    $data['email'],
                    $data['role'],
                    $data['password'],
                ]);

                return [
                    'success' => (bool) ($result->success ?? false),
                    'message' => (string) ($result->message ?? 'Account kon niet worden toegevoegd.'),
                    'account_id' => $result->account_id ?? null,
                ];
            }

            if (self::query()->where('email', $data['email'])->exists()) {
                return [
                    'success' => false,
                    'message' => 'deze email is al in gebruik',
                ];
            }

            $account = self::query()->create($data);

            return [
                'success' => true,
                'message' => 'account is toegevoegd',
                'account_id' => $account->id,
            ];
        } catch (Throwable $exception) {
            self::accountLogger()->error('Fout bij aanmaken van account.', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'Account kon niet worden toegevoegd.',
            ];
        }
    }

    private static function accountLogger(): LoggerInterface
    {
        return Log::build([
            'driver' => 'single',
            'path' => database_path(self::ACCOUNT_LOG_FILE),
        ]);
    }
}
