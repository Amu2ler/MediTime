<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Slot;
use Carbon\Carbon;

class SlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->get();
        $startDate = Carbon::tomorrow();
        $endDate = Carbon::create(2026, 3, 31);

        foreach ($doctors as $doctor) {
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                // Skip Sundays (optional, keeping it realistic)
                if ($currentDate->isSunday()) {
                    $currentDate->addDay();
                    continue;
                }

                // Morning: 09:00 -> 12:00 (Last slot starts at 11:30)
                $this->createSlots($doctor, $currentDate, 9, 12);

                // Afternoon: 13:00 -> 17:00 (Last slot starts at 16:30)
                $this->createSlots($doctor, $currentDate, 13, 17);

                $currentDate->addDay();
            }
        }
    }

    private function createSlots($doctor, $date, $startHour, $endHour)
    {
        for ($h = $startHour; $h < $endHour; $h++) {
            // :00
            $start1 = $date->copy()->setHour($h)->setMinute(0)->setSecond(0);
            $this->createSlotIfNotExists($doctor, $start1);

            // :30
            $start2 = $date->copy()->setHour($h)->setMinute(30)->setSecond(0);
            $this->createSlotIfNotExists($doctor, $start2);
        }
    }

    private function createSlotIfNotExists($doctor, $start)
    {
        $end = $start->copy()->addMinutes(30);

        // Check availability strictly to avoid duplicates if re-running
        $exists = Slot::where('user_id', $doctor->id)
            ->where('start_time', $start)
            ->exists();

        if (!$exists) {
            Slot::create([
                'user_id' => $doctor->id,
                'start_time' => $start,
                'end_time' => $end,
                'is_booked' => false,
            ]);
        }
    }
}
