<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;
use Throwable;

class BetalingController extends Controller
{
    private const LOG_FILE = 'logs/betalingen.log';

    public function index(Request $request): View
    {
        $zoekterm = trim((string) $request->query('zoekterm', ''));
        $geselecteerdeStatus = trim((string) $request->query('status', ''));
        $geselecteerdeBetaalmethode = trim((string) $request->query('betaalmethode', ''));
        $betalingen = collect();

        try {
            $betalingen = $this->getBetalingen($zoekterm, $geselecteerdeStatus, $geselecteerdeBetaalmethode);

            $this->logger()->info('Betalingsoverzicht succesvol opgehaald.', [
                'zoekterm' => $zoekterm ?: 'geen',
                'status' => $geselecteerdeStatus ?: 'alle',
                'betaalmethode' => $geselecteerdeBetaalmethode ?: 'alle',
                'aantal' => $betalingen->count(),
            ]);
        } catch (Throwable $exception) {
            $this->logger()->error('Fout bij ophalen betalingsoverzicht.', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        }

        return view('betalingen.index', [
            'betalingen' => $betalingen,
            'betaalmethodes' => $this->betaalmethodes(),
            'statussen' => $this->statussen(),
            'totalen' => $this->berekenTotalen($betalingen),
            'zoekterm' => $zoekterm,
            'geselecteerdeStatus' => $geselecteerdeStatus,
            'geselecteerdeBetaalmethode' => $geselecteerdeBetaalmethode,
        ]);
    }

    public function create(): View
    {
        return view('betalingen.create', [
            'klanten' => $this->getKlanten(),
            'betaalmethodes' => $this->betaalmethodes(),
            'statussen' => $this->statussen(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'KlantId' => ['required', 'integer', 'exists:users,id'],
            'Bedrag' => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'Betaalmethode' => ['required', 'in:Contant,Pin,iDEAL,Bankoverschrijving'],
            'Status' => ['required', 'in:Open,Betaald,Mislukt'],
            'Opmerking' => ['required', 'string', 'max:255'],
        ]);

        if (! User::query()->where('id', $data['KlantId'])->where('role', 'leerling')->exists()) {
            return back()
                ->withErrors(['KlantId' => 'Selecteer een geldige leerling.'])
                ->withInput();
        }

        if ($this->betalingRedenBestaat($data['KlantId'], $data['Opmerking'])) {
            return back()
                ->withErrors(['Opmerking' => 'Deze reden bestaat al bij deze leerling. Kies een andere reden.'])
                ->withInput();
        }

        try {
            DB::table('betalingen')->insert([
                'KlantId' => $data['KlantId'],
                'Bedrag' => $data['Bedrag'],
                'Betaalmethode' => $data['Betaalmethode'],
                'Status' => $data['Status'],
                'Opmerking' => $data['Opmerking'],
                'IsActief' => 1,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ]);

            $this->logger()->info('Betaling succesvol toegevoegd.', [
                'klant_id' => $data['KlantId'],
                'bedrag' => $data['Bedrag'],
                'status' => $data['Status'],
            ]);

            return redirect()
                ->route('betalingen.index')
                ->with('success', 'De betaling is toegevoegd en staat in het betalingsoverzicht.');
        } catch (Throwable $exception) {
            $this->logger()->error('Fout bij toevoegen betaling.', [
                'message' => $exception->getMessage(),
                'klant_id' => $data['KlantId'],
            ]);

            return back()
                ->withErrors(['Betaling' => 'De betaling kon niet worden toegevoegd. Probeer het later opnieuw.'])
                ->withInput();
        }
    }

    private function getBetalingen(string $zoekterm, string $status, string $betaalmethode): Collection
    {
        if (DB::getDriverName() === 'mysql') {
            return collect(DB::select('CALL sp_get_betalingen_overzicht(?, ?, ?)', [
                $zoekterm,
                $status,
                $betaalmethode,
            ]));
        }

        return $this->getBetalingenViaQueryBuilder($zoekterm, $status, $betaalmethode);
    }

    private function getBetalingenViaQueryBuilder(string $zoekterm, string $status, string $betaalmethode): Collection
    {
        $query = DB::table('betalingen as b')
            ->select([
                'b.Id',
                'u.name as KlantNaam',
                'u.email as KlantEmail',
                'b.Bedrag',
                'b.Betaalmethode',
                'b.Status',
                'b.Opmerking',
                'b.DatumAangemaakt',
            ])
            ->join('users as u', 'u.id', '=', 'b.KlantId')
            ->where('b.IsActief', 1);

        if ($zoekterm !== '') {
            $query->where(function ($query) use ($zoekterm): void {
                $query->where('u.name', 'like', "%{$zoekterm}%")
                    ->orWhere('u.email', 'like', "%{$zoekterm}%")
                    ->orWhere('b.Betaalmethode', 'like', "%{$zoekterm}%")
                    ->orWhere('b.Status', 'like', "%{$zoekterm}%")
                    ->orWhere('b.Opmerking', 'like', "%{$zoekterm}%");
            });
        }

        if ($status !== '') {
            $query->where('b.Status', $status);
        }

        if ($betaalmethode !== '') {
            $query->where('b.Betaalmethode', $betaalmethode);
        }

        return $query
            ->orderByDesc('b.DatumAangemaakt')
            ->orderByDesc('b.Id')
            ->get();
    }

    /**
     * @return array{totaal: float, betaald: float, open: float, openAantal: int}
     */
    private function berekenTotalen(Collection $betalingen): array
    {
        return [
            'totaal' => (float) $betalingen->sum('Bedrag'),
            'betaald' => (float) $betalingen->where('Status', 'Betaald')->sum('Bedrag'),
            'open' => (float) $betalingen->where('Status', 'Open')->sum('Bedrag'),
            'openAantal' => $betalingen->where('Status', 'Open')->count(),
        ];
    }

    private function betalingRedenBestaat(int|string $klantId, string $reden): bool
    {
        return DB::table('betalingen')
            ->where('KlantId', $klantId)
            ->where('Opmerking', $reden)
            ->where('IsActief', 1)
            ->exists();
    }

    private function getKlanten(): Collection
    {
        return User::query()
            ->where('role', 'leerling')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'created_at']);
    }

    /**
     * @return array<int, string>
     */
    private function betaalmethodes(): array
    {
        return ['Contant', 'Pin', 'iDEAL', 'Bankoverschrijving'];
    }

    /**
     * @return array<int, string>
     */
    private function statussen(): array
    {
        return ['Open', 'Betaald', 'Mislukt'];
    }

    private function logger(): LoggerInterface
    {
        return Log::build([
            'driver' => 'single',
            'path' => database_path(self::LOG_FILE),
        ]);
    }
}
