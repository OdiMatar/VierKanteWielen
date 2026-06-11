<x-layout title="Betalingen">
    <section class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-3">
        <div>
            <h1 class="h3 mb-1">Betalingen</h1>
            <p class="text-muted mb-0">Aantal geregistreerde betalingen: [{{ count($betalingen) }}]</p>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">Controleer de invoer en probeer het opnieuw.</div>
    @endif

    <div class="bg-white border rounded-3 p-3 mb-3">
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
                <button type="submit" class="btn btn-primary">Toevoegen</button>
            </div>
        </form>
    </div>

    <div class="bg-white border rounded-3 p-3 mb-3">
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

    <div class="table-responsive bg-white border rounded-3">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Betalingsnummer</th>
                    <th>Klantnaam</th>
                    <th>Bedrag</th>
                    <th>Betaalmethode</th>
                    <th>Status</th>
                    <th>Reden</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($betalingen as $betaling)
                    <tr>
                        <td class="fw-bold">{{ $betaling->Id }}</td>
                        <td>{{ $betaling->KlantNaam }}</td>
                        <td class="text-success fw-bold">EUR {{ number_format((float) $betaling->Bedrag, 2, ',', '.') }}</td>
                        <td>{{ $betaling->Betaalmethode }}</td>
                        <td>{{ $betaling->Status }}</td>
                        <td>{{ $betaling->Opmerking }}</td>
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
