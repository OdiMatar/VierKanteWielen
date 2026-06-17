<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('accounts.index', [
            'accounts' => Account::allViaStoredProcedure(),
        ]);
    }

    public function create(): View
    {
        return view('accounts.create', [
            'roles' => $this->roles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::in(array_keys($this->roles()))],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $result = Account::createViaStoredProcedure($data);

        if (! $result['success']) {
            return back()
                ->withErrors(['email' => $result['message']])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        return redirect()
            ->route('accounts.index')
            ->with('success', $result['message']);
    }

    /**
     * @return array<string, string>
     */
    private function roles(): array
    {
        return [
            'administrator' => 'Administrator',
            'instructeur' => 'Instructeur',
            'leerling' => 'Leerling',
        ];
    }
}
