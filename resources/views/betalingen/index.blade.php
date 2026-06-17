<x-layout title="Betalingen">
    <section class="payments-header mb-3">
        <div>
            <p class="payments-kicker">Financieel overzicht</p>
            <h1 class="h3 mb-1">Betalingen</h1>
            <p class="mb-0">Bekijk en registreer betalingen van leerlingen.</p>
        </div>
        <div class="payments-header-actions">
            <a class="btn btn-primary payments-new-button" href="{{ route('betalingen.create') }}">Nieuwe betaling</a>
            <div class="payments-total">
                <span>Aantal betalingen</span>
                <strong>{{ count($betalingen) }}</strong>
            </div>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">Controleer de invoer en probeer het opnieuw.</div>
    @endif

    <div class="payments-toolbar mb-3">
        <form method="get" action="{{ route('betalingen.index') }}" class="row g-2 align-items-end">
            <div class="col-md-9">
                <label for="zoekterm" class="form-label mb-1">Zoekterm</label>
                <input id="zoekterm" name="zoekterm" type="search" value="{{ $zoekterm }}" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-primary">Zoeken</button>
            </div>
        </form>
    </div>

    @if ($betalingen->isEmpty())
        <div class="alert alert-info">
            @if ($zoekterm !== '')
                Er zijn geen betalingen gevonden voor deze zoekterm.
            @else
                Er zijn momenteel geen betalingen beschikbaar. Probeer het later opnieuw.
            @endif
        </div>
    @endif

    <div class="payments-table-wrap">
        <table class="table payments-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nr.</th>
                    <th>Klantnaam</th>
                    <th>Bedrag</th>
                    <th>Betaalmethode</th>
                    <th>Status</th>
                    <th>Reden</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($betalingen as $betaling)
                    @php
                        $statusClass = match ($betaling->Status) {
                            'Betaald' => 'is-paid',
                            'Open' => 'is-open',
                            'Mislukt' => 'is-failed',
                            default => 'is-neutral',
                        };
                    @endphp
                    <tr>
                        <td data-label="Nr."><span class="payments-id">#{{ $betaling->Id }}</span></td>
                        <td data-label="Klant">
                            <div class="payments-customer">
                                <span>{{ substr($betaling->KlantNaam, 0, 1) }}</span>
                                <strong>{{ $betaling->KlantNaam }}</strong>
                            </div>
                        </td>
                        <td class="payments-amount" data-label="Bedrag">EUR {{ number_format((float) $betaling->Bedrag, 2, ',', '.') }}</td>
                        <td data-label="Methode"><span class="payments-method">{{ $betaling->Betaalmethode }}</span></td>
                        <td data-label="Status"><span class="payments-status {{ $statusClass }}">{{ $betaling->Status }}</span></td>
                        <td class="payments-reason" data-label="Reden">{{ $betaling->Opmerking }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Geen betalingen beschikbaar</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
