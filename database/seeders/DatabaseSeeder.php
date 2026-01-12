<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'patient',
            ]);
        }
        
        // Admin User
        if (!User::where('email', 'admin@meditime.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@meditime.com',
                'password' => bcrypt('password'), // Explicit password
                'role' => 'admin',
            ]);
        }

        $this->call([
            SpecialtySeeder::class,
            ConsultationReasonSeeder::class, // Added
            DoctorSeeder::class,
            PatientSeeder::class,
        ]);
    }
}
