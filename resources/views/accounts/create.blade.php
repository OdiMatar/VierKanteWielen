<x-layout title="Account aanmaken">
    <section class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Account aanmaken</h1>
            <p class="text-muted mb-0">Voeg een nieuw account toe voor de rijschoolomgeving.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('accounts.index') }}">Terug naar accounts</a>
    </section>

    <div class="bg-white border rounded-3 p-4" style="max-width: 560px;">
        <form method="post" action="{{ route('accounts.store') }}" class="d-grid gap-3">
            @csrf

            <label class="form-label mb-0">
                Naam
                <input class="form-control mt-1" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
            </label>

            <label class="form-label mb-0">
                E-mailadres
                <input class="form-control mt-1" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
            </label>

            <label class="form-label mb-0">
                Rol
                <select class="form-select mt-1" name="role" required>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', 'leerling') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<span class="text-danger small">{{ $message }}</span>@enderror
            </label>

            <label class="form-label mb-0">
                Wachtwoord
                <input class="form-control mt-1" type="password" name="password" required>
                @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
            </label>

            <label class="form-label mb-0">
                Herhaal wachtwoord
                <input class="form-control mt-1" type="password" name="password_confirmation" required>
            </label>

            <button class="btn btn-primary" type="submit">Account aanmaken</button>
        </form>
    </div>
</x-layout>
