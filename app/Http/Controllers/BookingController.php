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
        // ... (Existing Loads)
        $doctor->load(['specialty.consultationReasons']);
        $consultationReasons = $doctor->specialty->consultationReasons;

        // Reschedule Logic
        $rescheduleAppointment = null;
        if ($request->has('reschedule_id')) {
            $rescheduleAppointment = Appointment::where('id', $request->reschedule_id)
                ->where('patient_id', Auth::id())
                ->first();
        }

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

        return view('patient.booking.create', compact('doctor', 'slots', 'startOfWeek', 'previousWeek', 'nextWeek', 'currentDate', 'consultationReasons', 'selectedSlot', 'rescheduleAppointment'));
    }

    // Handle the reservation
    public function store(Request $request)
    {
        $request->validate([
            'slot_id' => ['required', 'exists:slots,id'],
            'reason_id' => ['required', 'exists:consultation_reasons,id'],
            'reason' => ['nullable', 'string', 'max:500'],
            'reschedule_id' => ['nullable', 'exists:appointments,id'],
        ]);

        $slot = Slot::findOrFail($request->slot_id);

        // Check availability again
        if ($slot->is_booked) {
            return back()->withErrors(['slot_id' => 'Ce créneau n\'est plus disponible.']);
        }

        // Transaction for Rescheduling safety
        \DB::transaction(function () use ($request, $slot) {
            
            // Prepare Reason String
            $specialtyReason = \App\Models\ConsultationReason::find($request->reason_id);
            $finalReason = $specialtyReason->name;
            if ($request->filled('reason')) {
                $finalReason .= ' - ' . $request->reason;
            }

            // Reschedule existing or Create new
            if ($request->filled('reschedule_id')) {
                $appointment = Appointment::where('id', $request->reschedule_id)
                    ->where('patient_id', Auth::id())
                    ->firstOrFail();

                // Free the OLD slot
                $oldSlot = $appointment->slot;
                $oldSlot->update(['is_booked' => false]);

                // Update Appointment to NEW slot
                $appointment->update([
                    'slot_id' => $slot->id,
                    'reason' => $finalReason,
                    'status' => 'confirmed', // Re-confirm if it was something else
                ]);
            } else {
                // Create Appointment
                Appointment::create([
                    'patient_id' => Auth::id(),
                    'slot_id' => $slot->id,
                    'reason' => $finalReason,
                    'status' => 'confirmed',
                ]);
            }

            // Book the NEW slot
            $slot->update(['is_booked' => true]);
        });

        $message = $request->filled('reschedule_id') ? 'Rendez-vous modifié avec succès !' : 'Rendez-vous confirmé !';

        return redirect()->route('dashboard')->with('success', $message);
    }
}
