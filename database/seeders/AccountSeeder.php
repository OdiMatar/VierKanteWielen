<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Seed the application's accounts.
     */
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'name' => 'Administrator Autorijschool',
            'email' => 'admin@autorijschool.test',
            'password' => 'password',
            'role' => 'administrator',
        ]);

        User::create([
            'name' => 'Instructeur Demo',
            'email' => 'instructeur@autorijschool.test',
            'password' => 'password',
            'role' => 'instructeur',
        ]);

        User::create([
            'name' => 'Leerling Demo',
            'email' => 'leerling@autorijschool.test',
            'password' => 'password',
            'role' => 'leerling',
        ]);

        User::create([
            'name' => 'Sanne Jansen',
            'email' => 'sanne.jansen@autorijschool.test',
            'password' => 'password',
            'role' => 'leerling',
        ]);

        User::create([
            'name' => 'Yassin El Amrani',
            'email' => 'yassin.elamrani@autorijschool.test',
            'password' => 'password',
            'role' => 'leerling',
        ]);

        User::create([
            'name' => 'Noor de Vries',
            'email' => 'noor.devries@autorijschool.test',
            'password' => 'password',
            'role' => 'leerling',
        ]);

        User::create([
            'name' => 'Milan Bakker',
            'email' => 'milan.bakker@autorijschool.test',
            'password' => 'password',
            'role' => 'leerling',
        ]);

        User::create([
            'name' => 'Emma Vermeer',
            'email' => 'emma.vermeer@autorijschool.test',
            'password' => 'password',
            'role' => 'leerling',
        ]);
    }
}
