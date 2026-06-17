<x-layout title="Wijzigen voertuiggegevens">
    <section class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-0">Wijzigen voertuiggegevens</h1>
        </div>
        <a class="btn btn-secondary" href="{{ route('instructeurs.voertuigen.index', $instructeur) }}">Terug</a>
    </section>

    <form class="card card-body border shadow-sm" method="post" action="{{ route('instructeurs.voertuigen.update', [$instructeur, $voertuig->Id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="InstructeurId" class="form-label">Instructeur</label>
            <select id="InstructeurId" class="form-select @error('InstructeurId') is-invalid @enderror" name="InstructeurId" required>
                @foreach ($instructeurs as $rijInstructeur)
                    <option value="{{ $rijInstructeur->Id }}" @selected((int) old('InstructeurId', $voertuig->InstructeurId ?? $instructeur->Id) === $rijInstructeur->Id)>
                        {{ $rijInstructeur->VolledigeNaam }}
                    </option>
                @endforeach
            </select>
            @error('InstructeurId')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="TypeVoertuigId" class="form-label">Type voertuig</label>
            <select id="TypeVoertuigId" class="form-select @error('TypeVoertuigId') is-invalid @enderror" name="TypeVoertuigId" required>
                @foreach ($typeVoertuigen as $typeVoertuig)
                    <option value="{{ $typeVoertuig->Id }}" @selected((int) old('TypeVoertuigId', $voertuig->TypeVoertuigId) === $typeVoertuig->Id)>
                        {{ $typeVoertuig->TypeVoertuig }}
                    </option>
                @endforeach
            </select>
            @error('TypeVoertuigId')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Type" class="form-label">Type</label>
            <input id="Type" class="form-control @error('Type') is-invalid @enderror" name="Type" value="{{ old('Type', $voertuig->Type) }}" maxlength="80" required>
            @error('Type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Bouwjaar" class="form-label">Bouwjaar</label>
            <input id="Bouwjaar" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($voertuig->Bouwjaar)->format('d-m-Y') }}" readonly>
        </div>

        <fieldset class="mb-3">
            <legend class="col-form-label pt-0">Brandstof</legend>
            @foreach ($brandstoffen as $brandstof)
                <div class="form-check form-check-inline">
                    <input id="Brandstof{{ $loop->index }}" class="form-check-input @error('Brandstof') is-invalid @enderror" type="radio" name="Brandstof" value="{{ $brandstof }}" @checked(old('Brandstof', $voertuig->Brandstof) === $brandstof) required>
                    <label class="form-check-label" for="Brandstof{{ $loop->index }}">{{ $brandstof }}</label>
                </div>
            @endforeach
            @error('Brandstof')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </fieldset>

        <div class="mb-3">
            <label for="Kenteken" class="form-label">Kenteken</label>
            <input id="Kenteken" class="form-control @error('Kenteken') is-invalid @enderror" name="Kenteken" value="{{ old('Kenteken', $voertuig->Kenteken) }}" maxlength="10" required>
            @error('Kenteken')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" type="submit">Wijzig</button>
        </div>
    </form>
</x-layout>
