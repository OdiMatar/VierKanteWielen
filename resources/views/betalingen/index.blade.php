<x-layout title="Betalingen">
    <section class="payments-header mb-3">
        <div>
            <p class="payments-kicker">Financieel overzicht</p>
            <h1 class="h3 mb-1">Betalingen</h1>
            <p class="mb-0">Bekijk en registreer betalingen van leerlingen.</p>
        </div>
        <div class="payments-total">
            <span>Aantal betalingen</span>
            <strong>{{ count($betalingen) }}</strong>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">Controleer de invoer en probeer het opnieuw.</div>
    @endif

    <div class="payments-panel mb-3">
        <div class="payments-panel-heading">
            <h2>Nieuwe betaling</h2>
        </div>
        <form method="post" action="{{ route('betalingen.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6 col-xl-3">
                <label for="KlantId" class="form-label">Leerling</label>
                <select id="KlantId" name="KlantId" class="form-select @error('KlantId') is-invalid @enderror" required>
                    <option value="">Kies een leerling</option>
                    @foreach ($klanten as $klant)
                        <option value="{{ $klant->id }}" @selected((int) old('KlantId') === $klant->id)>{{ $klant->name }}</option>
                    @endforeach
                </select>
                @error('KlantId')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-2">
                <label for="Bedrag" class="form-label">Bedrag</label>
                <input id="Bedrag" name="Bedrag" type="number" min="0.01" max="99999.99" step="0.01" value="{{ old('Bedrag') }}" class="form-control @error('Bedrag') is-invalid @enderror" required>
                @error('Bedrag')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-2">
                <label for="Betaalmethode" class="form-label">Betaalmethode</label>
                <select id="Betaalmethode" name="Betaalmethode" class="form-select @error('Betaalmethode') is-invalid @enderror" required>
                    <option value="">Kies methode</option>
                    @foreach ($betaalmethodes as $betaalmethode)
                        <option value="{{ $betaalmethode }}" @selected(old('Betaalmethode') === $betaalmethode)>{{ $betaalmethode }}</option>
                    @endforeach
                </select>
                @error('Betaalmethode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-2">
                <label for="Status" class="form-label">Status</label>
                <select id="Status" name="Status" class="form-select @error('Status') is-invalid @enderror" required>
                    <option value="">Kies status</option>
                    @foreach ($statussen as $status)
                        <option value="{{ $status }}" @selected(old('Status', 'Open') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                @error('Status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-8 col-xl-2">
                <label for="Opmerking" class="form-label">Reden</label>
                <input id="Opmerking" name="Opmerking" type="text" maxlength="255" value="{{ old('Opmerking') }}" class="form-control @error('Opmerking') is-invalid @enderror" required>
                @error('Opmerking')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 col-xl-1 d-grid align-self-end">
                <button type="submit" class="btn btn-primary payments-submit">Toevoegen</button>
            </div>
        </form>
    </div>

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
