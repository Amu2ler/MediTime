<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Show available slots for a specific doctor
    public function create(Request $request, DoctorProfile $doctor)
    {
        // Load specialty and its reasons
        $doctor->load(['specialty.consultationReasons']);
        $consultationReasons = $doctor->specialty->consultationReasons;

        // Check if a specific slot was selected from Search
        $selectedSlot = null;
        if ($request->has('slot_id')) {
            $selectedSlot = Slot::find($request->slot_id);
        }

        // Calendar Logic (Weekly)
        $currentDate = $request->has('date') ? Carbon::parse($request->input('date')) : now();
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();

        // Previous and Next Week Links
        $previousWeek = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $startOfWeek->copy()->addWeek()->format('Y-m-d');

        // Fetch future, unbooked slots for this doctor
        $slots = Slot::where('user_id', $doctor->user_id)
            ->where('is_booked', false)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->start_time->format('Y-m-d');
            });

        return view('patient.booking.create', compact('doctor', 'slots', 'startOfWeek', 'previousWeek', 'nextWeek', 'currentDate', 'consultationReasons', 'selectedSlot'));
    }

    // Handle the reservation
    public function store(Request $request)
    {
        $request->validate([
            'slot_id' => ['required', 'exists:slots,id'],
            'reason_id' => ['required', 'exists:consultation_reasons,id'],
            'reason' => ['nullable', 'string', 'max:500'], // This is the optional note
        ]);

        $slot = Slot::findOrFail($request->slot_id);

        // Check availability again
        if ($slot->is_booked) {
            return back()->withErrors(['slot_id' => 'Ce créneau n\'est plus disponible.']);
        }

        // Fetch the selected reason name
        $specialtyReason = \App\Models\ConsultationReason::find($request->reason_id);
        
        // Combine Reason Name + Patient Note
        $finalReason = $specialtyReason->name;
        if ($request->filled('reason')) {
            $finalReason .= ' - ' . $request->reason;
        }

        // Create appointment
        Appointment::create([
            'patient_id' => Auth::id(),
            'slot_id' => $slot->id,
            'reason' => $finalReason,
            'status' => 'confirmed',
        ]);

        // Mark slot as booked
        $slot->update(['is_booked' => true]);

        return redirect()->route('dashboard')->with('success', 'Rendez-vous confirmé !');
    }
}
