<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Specialty;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure specialties exist
        if (Specialty::count() === 0) {
            $this->call(SpecialtySeeder::class);
        }

        $specialties = Specialty::all();

        // Create 20 Doctors
        $doctors = User::factory(20)->create([
            'role' => 'doctor',
            'password' => Hash::make('password'),
        ]);

        foreach ($doctors as $doctor) {
            // Create Profile
            DoctorProfile::create([
                'user_id' => $doctor->id,
                'specialty_id' => $specialties->random()->id,
                'bio' => 'Médecin expérimenté, diplômé de la faculté de médecine. Spécialiste à l\'écoute de ses patients.',
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'zip_code' => fake()->postcode(),
                'phone' => fake()->phoneNumber(),
            ]);

            // Create Slots for the next 7 days
            for ($i = 0; $i < 5; $i++) { // 5 days
                $date = Carbon::now()->addDays($i + 1);
                
                // 4 slots per day
                for ($h = 9; $h < 13; $h++) {
                    $start = $date->copy()->setHour($h)->setMinute(0)->setSecond(0);
                    $end = $start->copy()->addMinutes(30);

                    Slot::create([
                        'user_id' => $doctor->id,
                        'start_time' => $start,
                        'end_time' => $end,
                        'is_booked' => false,
                    ]);
                }
            }
        }
    }
}
