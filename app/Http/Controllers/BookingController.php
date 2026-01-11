<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Show available slots for a specific doctor
    public function create(DoctorProfile $doctor)
    {
        // Fetch future, unbooked slots for this doctor
        $slots = Slot::where('user_id', $doctor->user_id)
            ->where('start_time', '>', now())
            ->where('is_booked', false)
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->start_time->format('l d F Y'); // Group by day
            });

        return view('patient.booking.create', compact('doctor', 'slots'));
    }

    // Handle the reservation
    public function store(Request $request)
    {
        $request->validate([
            'slot_id' => ['required', 'exists:slots,id'],
        ]);

        $slot = Slot::findOrFail($request->slot_id);

        // Check availability again
        if ($slot->is_booked) {
            return back()->withErrors(['slot_id' => 'Ce créneau n\'est plus disponible.']);
        }

        // Create appointment
        Appointment::create([
            'patient_id' => Auth::id(),
            'slot_id' => $slot->id,
            'status' => 'confirmed',
        ]);

        // Mark slot as booked
        $slot->update(['is_booked' => true]);

        return redirect()->route('dashboard')->with('success', 'Rendez-vous confirmé !');
    }
}
