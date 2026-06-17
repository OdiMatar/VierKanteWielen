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
                <span>Betalingen</span>
                <strong>{{ count($betalingen) }}</strong>
            </div>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">Controleer de invoer en probeer het opnieuw.</div>
    @endif

    <section class="payment-summary-grid mb-3">
        <article>
            <span>Totaal zichtbaar</span>
            <strong>EUR {{ number_format($totalen['totaal'], 2, ',', '.') }}</strong>
        </article>
        <article>
            <span>Betaald</span>
            <strong>EUR {{ number_format($totalen['betaald'], 2, ',', '.') }}</strong>
        </article>
        <article>
            <span>Openstaand</span>
            <strong>EUR {{ number_format($totalen['open'], 2, ',', '.') }}</strong>
            <small>{{ $totalen['openAantal'] }} open betaling(en)</small>
        </article>
    </section>

    <div class="payments-toolbar mb-3">
        <form method="get" action="{{ route('betalingen.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="zoekterm" class="form-label mb-1">Zoekterm</label>
                <input id="zoekterm" name="zoekterm" type="search" value="{{ $zoekterm }}" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label mb-1">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Alle statussen</option>
                    @foreach ($statussen as $status)
                        <option value="{{ $status }}" @selected($geselecteerdeStatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="betaalmethode" class="form-label mb-1">Betaalmethode</label>
                <select id="betaalmethode" name="betaalmethode" class="form-select">
                    <option value="">Alle methodes</option>
                    @foreach ($betaalmethodes as $betaalmethode)
                        <option value="{{ $betaalmethode }}" @selected($geselecteerdeBetaalmethode === $betaalmethode)>{{ $betaalmethode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">Zoeken</button>
            </div>
            @if ($zoekterm !== '' || $geselecteerdeStatus !== '' || $geselecteerdeBetaalmethode !== '')
                <div class="col-12">
                    <a class="payments-reset-link" href="{{ route('betalingen.index') }}">Filters wissen</a>
                </div>
            @endif
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

    <div class="data-table-wrap payments-table-wrap">
        <table class="table data-table payments-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nr.</th>
                    <th>Leerling</th>
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
                        <td data-label="Nr.">
                            <div class="payments-number">
                                <span>#{{ $betaling->Id }}</span>
                                <small>{{ \Illuminate\Support\Carbon::parse($betaling->DatumAangemaakt)->format('d-m-Y H:i') }}</small>
                            </div>
                        </td>
                        <td data-label="Leerling">
                            <div class="payments-customer">
                                <span>{{ substr($betaling->KlantNaam, 0, 1) }}</span>
                                <div>
                                    <strong>{{ $betaling->KlantNaam }}</strong>
                                    <small>{{ $betaling->KlantEmail }}</small>
                                </div>
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
