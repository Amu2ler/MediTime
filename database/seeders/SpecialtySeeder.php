<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Médecine générale',
            'Dermatologie',
            'Cardiologie',
            'Pédiatrie',
            'Gynécologie',
            'Ophtalmologie',
            'ORL',
            'Dentiste',
            'Psychiatrie',
        ];

        foreach ($names as $name) {
            Specialty::firstOrCreate(['name' => $name]);
        }
    }
}
