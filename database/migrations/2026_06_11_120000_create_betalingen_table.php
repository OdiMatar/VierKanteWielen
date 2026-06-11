<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('betalingen')) {
            Schema::create('betalingen', function (Blueprint $table) {
                $table->id('Id');
                $table->foreignId('KlantId')->constrained('users')->cascadeOnDelete();
                $table->decimal('Bedrag', 8, 2);
                $table->string('Betaalmethode', 40);
                $table->string('Status', 30);
                $table->boolean('IsActief')->default(true);
                $table->string('Opmerking')->nullable();
                $table->timestamp('DatumAangemaakt')->nullable();
                $table->timestamp('DatumGewijzigd')->nullable();
            });
        }

        $leerlingen = [
            ['name' => 'Leerling Demo', 'email' => 'leerling@autorijschool.test'],
            ['name' => 'Sanne Jansen', 'email' => 'sanne.jansen@autorijschool.test'],
            ['name' => 'Yassin El Amrani', 'email' => 'yassin.elamrani@autorijschool.test'],
            ['name' => 'Noor de Vries', 'email' => 'noor.devries@autorijschool.test'],
            ['name' => 'Milan Bakker', 'email' => 'milan.bakker@autorijschool.test'],
        ];

        foreach ($leerlingen as $leerling) {
            if (DB::table('users')->where('email', $leerling['email'])->exists()) {
                continue;
            }

            DB::table('users')->insert([
                'name' => $leerling['name'],
                'email' => $leerling['email'],
                'role' => 'leerling',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('betalingen');
    }
};
