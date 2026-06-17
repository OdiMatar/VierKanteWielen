<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AccountAanmakenTest extends TestCase
{
    use DatabaseTransactions;

    public function test_nieuw_account_bestaat_niet_en_wordt_toegevoegd(): void
    {
        $administrator = User::factory()->create(['role' => 'administrator']);

        $this->actingAs($administrator)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Accounts');

        $this->actingAs($administrator)
            ->get(route('accounts.index'))
            ->assertOk()
            ->assertSee('Account aanmaken');

        $this->actingAs($administrator)
            ->post(route('accounts.store'), [
                'name' => 'Nieuwe Leerling',
                'email' => 'nieuwe.leerling@autorijschool.test',
                'role' => 'leerling',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertRedirect(route('accounts.index'))
            ->assertSessionHas('success', 'account is toegevoegd');

        $this->assertDatabaseHas('users', [
            'name' => 'Nieuwe Leerling',
            'email' => 'nieuwe.leerling@autorijschool.test',
            'role' => 'leerling',
        ]);
    }

    public function test_nieuw_account_bestaat_wel_en_email_melding_wordt_getoond(): void
    {
        $administrator = User::factory()->create(['role' => 'administrator']);

        User::factory()->create([
            'name' => 'Bestaande Leerling',
            'email' => 'bestaand@autorijschool.test',
            'role' => 'leerling',
        ]);

        $this->actingAs($administrator)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Accounts');

        $this->actingAs($administrator)
            ->get(route('accounts.index'))
            ->assertOk()
            ->assertSee('Account aanmaken');

        $this->actingAs($administrator)
            ->from(route('accounts.create'))
            ->post(route('accounts.store'), [
                'name' => 'Dubbele Leerling',
                'email' => 'bestaand@autorijschool.test',
                'role' => 'leerling',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertRedirect(route('accounts.create'))
            ->assertSessionHasErrors(['email' => 'deze email is al in gebruik']);
    }
}
