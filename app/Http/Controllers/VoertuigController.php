<?php

namespace App\Http\Controllers;

use App\Models\Instructeur;
use App\Models\TypeVoertuig;
use App\Models\Voertuig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;
use Throwable;

class VoertuigController extends Controller
{
    private const LOG_FILE = 'logs/voertuigen.log';

    public function index(Instructeur $instructeur): View
    {
        $voertuigen = collect();

        try {
            $voertuigen = $this->getVoertuigenBijInstructeur($instructeur);

            $this->logger()->info('Voertuigen bij instructeur succesvol opgehaald.', [
                'instructeur_id' => $instructeur->Id,
                'aantal' => $voertuigen->count(),
            ]);
        } catch (Throwable $exception) {
            $this->logException('Fout bij ophalen voertuigen bij instructeur.', $exception, [
                'instructeur_id' => $instructeur->Id,
            ]);
        }

        return view('voertuigen.index', [
            'instructeur' => $instructeur,
            'voertuigen' => $voertuigen,
        ]);
    }

    public function beschikbaar(Instructeur $instructeur): View
    {
        $voertuigen = collect();

        try {
            $voertuigen = $this->getBeschikbareVoertuigen();

            $this->logger()->info('Beschikbare voertuigen succesvol opgehaald.', [
                'instructeur_id' => $instructeur->Id,
                'aantal' => $voertuigen->count(),
            ]);
        } catch (Throwable $exception) {
            $this->logException('Fout bij ophalen beschikbare voertuigen.', $exception, [
                'instructeur_id' => $instructeur->Id,
            ]);
        }

        return view('voertuigen.beschikbaar', [
            'instructeur' => $instructeur,
            'voertuigen' => $voertuigen,
        ]);
    }

    public function edit(Instructeur $instructeur, Voertuig $voertuig): View
    {
        $details = null;

        try {
            $details = $this->getVoertuigDetails($voertuig);

            $this->logger()->info('Voertuigdetails succesvol opgehaald.', [
                'voertuig_id' => $voertuig->Id,
            ]);
        } catch (Throwable $exception) {
            $this->logException('Fout bij ophalen voertuigdetails.', $exception, [
                'voertuig_id' => $voertuig->Id,
            ]);
        }

        abort_if($details === null, 404);

        return view('voertuigen.edit', [
            'instructeur' => $instructeur,
            'voertuig' => $details,
            'instructeurs' => Instructeur::query()->orderBy('Voornaam')->orderBy('Achternaam')->get(),
            'typeVoertuigen' => TypeVoertuig::query()->orderBy('Rijbewijscategorie')->get(),
            'brandstoffen' => ['Benzine', 'Diesel', 'Elektrisch'],
        ]);
    }

    public function update(Request $request, Instructeur $instructeur, Voertuig $voertuig): RedirectResponse
    {
        $data = $request->validate([
            'Kenteken' => ['required', 'string', 'max:10', Rule::unique('voertuigen', 'Kenteken')->ignore($voertuig->Id, 'Id')],
            'Type' => ['required', 'string', 'max:80'],
            'Brandstof' => ['required', Rule::in(['Benzine', 'Diesel', 'Elektrisch'])],
            'TypeVoertuigId' => ['required', 'exists:type_voertuigen,Id'],
            'InstructeurId' => ['required', 'exists:instructeurs,Id'],
        ]);

        try {
            DB::transaction(function () use ($voertuig, $data): void {
                $this->updateVoertuigGegevens($voertuig, $data);
                $this->updateVoertuigToewijzing($voertuig, $data);
            });

            $this->logger()->info('Voertuiggegevens succesvol gewijzigd.', [
                'voertuig_id' => $voertuig->Id,
                'instructeur_id' => $data['InstructeurId'],
            ]);
        } catch (Throwable $exception) {
            $this->logException('Fout bij wijzigen voertuiggegevens.', $exception, [
                'voertuig_id' => $voertuig->Id,
                'instructeur_id' => $data['InstructeurId'],
            ]);

            return back()
                ->withErrors(['Voertuig' => 'De voertuiggegevens konden niet worden gewijzigd. Probeer het later opnieuw.'])
                ->withInput();
        }

        return redirect()
            ->route('instructeurs.voertuigen.index', $instructeur)
            ->with('success', 'De voertuiggegevens zijn gewijzigd.');
    }

    private function getVoertuigenBijInstructeur(Instructeur $instructeur)
    {
        return DB::table('voertuigen as v')
            ->select([
                'v.Id',
                'v.Kenteken',
                'v.Type',
                'v.Bouwjaar',
                'v.Brandstof',
                'tv.TypeVoertuig',
                'tv.Rijbewijscategorie',
                'vi.DatumToekenning',
            ])
            ->join('voertuig_instructeur as vi', 'vi.VoertuigId', '=', 'v.Id')
            ->join('type_voertuigen as tv', 'tv.Id', '=', 'v.TypeVoertuigId')
            ->where('vi.InstructeurId', $instructeur->Id)
            ->where('v.IsActief', 1)
            ->where('vi.IsActief', 1)
            ->orderBy('tv.Rijbewijscategorie')
            ->orderBy('v.Type')
            ->get();
    }

    private function getBeschikbareVoertuigen()
    {
        return DB::table('voertuigen as v')
            ->select([
                'v.Id',
                'v.Kenteken',
                'v.Type',
                'v.Bouwjaar',
                'v.Brandstof',
                'tv.TypeVoertuig',
                'tv.Rijbewijscategorie',
            ])
            ->join('type_voertuigen as tv', 'tv.Id', '=', 'v.TypeVoertuigId')
            ->leftJoin('voertuig_instructeur as vi', 'vi.VoertuigId', '=', 'v.Id')
            ->whereNull('vi.Id')
            ->where('v.IsActief', 1)
            ->orderBy('tv.Rijbewijscategorie')
            ->orderBy('v.Type')
            ->get();
    }

    private function getVoertuigDetails(Voertuig $voertuig): ?object
    {
        return DB::table('voertuigen as v')
            ->select([
                'v.Id',
                'v.Kenteken',
                'v.Type',
                'v.Bouwjaar',
                'v.Brandstof',
                'v.TypeVoertuigId',
                'vi.InstructeurId',
            ])
            ->leftJoin('voertuig_instructeur as vi', 'vi.VoertuigId', '=', 'v.Id')
            ->where('v.Id', $voertuig->Id)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function updateVoertuigGegevens(Voertuig $voertuig, array $data): void
    {
        DB::table('voertuigen')
            ->where('Id', $voertuig->Id)
            ->update([
                'Kenteken' => $data['Kenteken'],
                'Type' => $data['Type'],
                'Brandstof' => $data['Brandstof'],
                'TypeVoertuigId' => $data['TypeVoertuigId'],
                'DatumGewijzigd' => now(),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function updateVoertuigToewijzing(Voertuig $voertuig, array $data): void
    {
        $bestaandeToewijzing = DB::table('voertuig_instructeur')
            ->where('VoertuigId', $voertuig->Id)
            ->first();

        if ($bestaandeToewijzing) {
            DB::table('voertuig_instructeur')
                ->where('Id', $bestaandeToewijzing->Id)
                ->update([
                    'InstructeurId' => $data['InstructeurId'],
                    'IsActief' => 1,
                    'DatumGewijzigd' => now(),
                ]);

            return;
        }

        DB::table('voertuig_instructeur')->insert([
            'VoertuigId' => $voertuig->Id,
            'InstructeurId' => $data['InstructeurId'],
            'DatumToekenning' => now()->toDateString(),
            'IsActief' => 1,
            'DatumAangemaakt' => now(),
            'DatumGewijzigd' => now(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function logException(string $message, Throwable $exception, array $context = []): void
    {
        $this->logger()->error($message, [
            ...$context,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
    }

    private function logger(): LoggerInterface
    {
        return Log::build([
            'driver' => 'single',
            'path' => database_path(self::LOG_FILE),
        ]);
    }
}
