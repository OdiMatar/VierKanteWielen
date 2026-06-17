<x-layout title="Accounts overzicht">
    <section class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Accounts</h1>
            <p class="text-muted mb-0">Overzicht van alle geregistreerde accounts.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('accounts.create') }}">Account aanmaken</a>
    </section>

    @if ($accounts->count() === 1 && $accounts->first()->role === 'administrator')
        <div class="alert alert-info mb-3">Er zijn geen accounts behalve de adminaccount.</div>
    @endif

    <div class="table-responsive bg-white border rounded-3">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>E-mailadres</th>
                    <th>Rol</th>
                    <th>Geregistreerd op</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->email }}</td>
                        <td><span class="badge text-bg-light border">{{ ucfirst($account->role) }}</span></td>
                        <td>{{ \Illuminate\Support\Carbon::parse($account->created_at)->format('d-m-Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
