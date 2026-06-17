<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BetalingSeeder extends Seeder
{
    /**
     * Seed five students with example payments and reasons.
     */
    public function run(): void
    {
        $leerlingen = [
            ['name' => 'Sanne Jansen', 'email' => 'sanne.jansen@autorijschool.test'],
            ['name' => 'Yassin El Amrani', 'email' => 'yassin.elamrani@autorijschool.test'],
            ['name' => 'Noor de Vries', 'email' => 'noor.devries@autorijschool.test'],
            ['name' => 'Milan Bakker', 'email' => 'milan.bakker@autorijschool.test'],
            ['name' => 'Emma Vermeer', 'email' => 'emma.vermeer@autorijschool.test'],
        ];

        foreach ($leerlingen as $leerling) {
            DB::table('users')->updateOrInsert(
                ['email' => $leerling['email']],
                [
                    'name' => $leerling['name'],
                    'role' => 'leerling',
                    'password' => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $betalingen = [
            [
                'email' => 'sanne.jansen@autorijschool.test',
                'Bedrag' => 549.99,
                'Betaalmethode' => 'iDEAL',
                'Status' => 'Betaald',
                'Opmerking' => 'Aanbetaling basispakket',
            ],
            [
                'email' => 'yassin.elamrani@autorijschool.test',
                'Bedrag' => 999.99,
                'Betaalmethode' => 'Bankoverschrijving',
                'Status' => 'Open',
                'Opmerking' => 'Termijnbetaling standaard pakket',
            ],
            [
                'email' => 'noor.devries@autorijschool.test',
                'Bedrag' => 59.99,
                'Betaalmethode' => 'Pin',
                'Status' => 'Betaald',
                'Opmerking' => 'Losse extra rijles',
            ],
            [
                'email' => 'milan.bakker@autorijschool.test',
                'Bedrag' => 1699.99,
                'Betaalmethode' => 'iDEAL',
                'Status' => 'Betaald',
                'Opmerking' => 'Premium pakket volledig betaald',
            ],
            [
                'email' => 'emma.vermeer@autorijschool.test',
                'Bedrag' => 110.00,
                'Betaalmethode' => 'Contant',
                'Status' => 'Open',
                'Opmerking' => 'Examentraining en praktijkexamen',
            ],
        ];

        foreach ($betalingen as $betaling) {
            $klantId = DB::table('users')
                ->where('email', $betaling['email'])
                ->value('id');

            DB::table('betalingen')->updateOrInsert(
                [
                    'KlantId' => $klantId,
                    'Opmerking' => $betaling['Opmerking'],
                ],
                [
                    'Bedrag' => $betaling['Bedrag'],
                    'Betaalmethode' => $betaling['Betaalmethode'],
                    'Status' => $betaling['Status'],
                    'IsActief' => 1,
                    'DatumAangemaakt' => now(),
                    'DatumGewijzigd' => now(),
                ]
            );
        }
    }
}
