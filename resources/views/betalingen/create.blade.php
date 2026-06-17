<x-layout title="Nieuwe betaling">
    <section class="payments-header mb-3">
        <div>
            <p class="payments-kicker">Nieuwe betaling</p>
            <h1 class="h3 mb-1">Betaling toevoegen</h1>
            <p class="mb-0">Registreer een betaling voor een leerling.</p>
        </div>
        <div class="payments-header-actions">
            <a class="btn btn-outline-secondary" href="{{ route('betalingen.index') }}">Terug naar overzicht</a>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">Controleer de invoer en probeer het opnieuw.</div>
    @endif

    <div class="payments-panel payments-create-panel">
        <div class="payments-panel-heading">
            <h2>Betaalgegevens</h2>
        </div>
        <form method="post" action="{{ route('betalingen.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6 col-xl-3">
                <label for="KlantId" class="form-label">Leerling</label>
                <select id="KlantId" name="KlantId" class="form-select @error('KlantId') is-invalid @enderror" required autofocus>
                    <option value="">Kies een leerling</option>
                    @foreach ($klanten as $klant)
                        <option value="{{ $klant->id }}" @selected((int) old('KlantId') === $klant->id)>{{ $klant->name }}</option>
                    @endforeach
                </select>
                @error('KlantId')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-3">
                <label for="Bedrag" class="form-label">Bedrag</label>
                <input id="Bedrag" name="Bedrag" type="number" min="0.01" max="99999.99" step="0.01" value="{{ old('Bedrag') }}" class="form-control @error('Bedrag') is-invalid @enderror" required>
                @error('Bedrag')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-3">
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

            <div class="col-md-6 col-xl-3">
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

            <div class="col-12">
                <label for="Opmerking" class="form-label">Reden</label>
                <input id="Opmerking" name="Opmerking" type="text" maxlength="255" value="{{ old('Opmerking') }}" class="form-control @error('Opmerking') is-invalid @enderror" required>
                @error('Opmerking')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                <a class="btn btn-outline-secondary" href="{{ route('betalingen.index') }}">Annuleren</a>
                <button type="submit" class="btn btn-primary payments-submit">Toevoegen</button>
            </div>
        </form>
    </div>
</x-layout>
