<x-layout title="Account aanmaken">
    <section class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Account aanmaken</h1>
            <p class="text-muted mb-0">Voeg een nieuw account toe voor de rijschoolomgeving.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('accounts.index') }}">Terug naar accounts</a>
    </section>

    <div class="bg-white border rounded-3 p-4 w-100">
        <form method="post" action="{{ route('accounts.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-6">
                <label class="form-label" for="name">Naam</label>
                <input class="form-control mt-1" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="email">E-mailadres</label>
                <input class="form-control mt-1" id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="col-12">
                <label class="form-label" for="role">Rol</label>
                <select class="form-select mt-1" id="role" name="role" required>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', 'leerling') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="password">Wachtwoord</label>
                <div class="input-group mt-1">
                    <input class="form-control" id="password" type="password" name="password" required>
                    <button class="btn btn-outline-secondary password-toggle" type="button" data-password-toggle="password" aria-label="Wachtwoord tonen" aria-pressed="false">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
                @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="password_confirmation">Herhaal wachtwoord</label>
                <div class="input-group mt-1">
                    <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required>
                    <button class="btn btn-outline-secondary password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Wachtwoord tonen" aria-pressed="false">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="col-12 d-grid d-sm-flex justify-content-sm-end">
                <button class="btn btn-primary" type="submit">Account aanmaken</button>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                button.setAttribute('aria-label', isPassword ? 'Wachtwoord verbergen' : 'Wachtwoord tonen');
                button.setAttribute('aria-pressed', String(isPassword));
            });
        });
    </script>
</x-layout>
