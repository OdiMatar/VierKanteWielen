<x-layout title="Lesrijpakketten Overzicht">
    <section class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Lesrijpakketten Overzicht</h1>
            <p class="text-muted mb-0">Aantal beschikbare pakketten: [{{ count($lesrijpakketten) }}]</p>
        </div>
    </section>

    <div class="bg-white border rounded-3 p-3 mb-3">
        <form method="get" action="{{ route('lesrijpakketten.index') }}" class="row g-2 align-items-end">
            <div class="col-md-9">
                <label for="categorie" class="form-label mb-1">Categorie</label>
                <select id="categorie" name="categorie" class="form-select">
                    <option value="">Alle categorieen</option>
                    @foreach ($categorieen as $categorie)
                        <option value="{{ $categorie }}" @selected($geselecteerdeCategorie === $categorie)>{{ $categorie }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-primary">Toon Lespakketten</button>
            </div>
        </form>
    </div>

    <div class="data-table-wrap">
        <table class="table data-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Aantal Lessen</th>
                    <th>Prijs</th>
                    <th>Categorie</th>
                    <th>Beschrijving</th>
                    <th>Info</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lesrijpakketten as $pakket)
                    <tr>
                        <td class="fw-bold text-dark">{{ $pakket->Naam }}</td>
                        <td><span class="data-pill">{{ $pakket->Lessen }} lessen</span></td>
                        <td class="data-amount">EUR {{ number_format($pakket->Prijs, 2, ',', '.') }}</td>
                        <td>
                            @if ($pakket->Categorie)
                                <span class="data-badge">{{ $pakket->Categorie }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($pakket->Beschrijving)
                                <small class="text-muted">{{ substr($pakket->Beschrijving, 0, 50) }}@if (strlen($pakket->Beschrijving) > 50)...@endif</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#pakketModal-{{ $pakket->id }}"
                            >
                                Info
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            @if ($geselecteerdeCategorie === 'Theorie')
                                Er zijn geen lespakketten bekend die behoren bij de geselecteerde categorie
                            @else
                                Geen lespakketten beschikbaar
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($lesrijpakketten as $pakket)
        <div class="modal fade" id="pakketModal-{{ $pakket->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5">{{ $pakket->Naam }}</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2"><strong>Aantal lessen:</strong> {{ $pakket->Lessen }}</p>
                        <p class="mb-2"><strong>Prijs:</strong> EUR {{ number_format($pakket->Prijs, 2, ',', '.') }}</p>
                        <p class="mb-2"><strong>Categorie:</strong> {{ $pakket->Categorie ?: '-' }}</p>
                        <p class="mb-2"><strong>Volledige beschrijving:</strong> {{ $pakket->Beschrijving ?: 'Geen beschrijving beschikbaar.' }}</p>
                        <p class="mb-0"><strong>Opmerking:</strong> {{ $pakket->Opmerking ?: 'Geen extra opmerkingen.' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>
