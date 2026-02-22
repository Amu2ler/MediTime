<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class DoctorPatientController extends Controller
{
    public function index()
    {
        $doctor = Auth::user();

        // 1. Get unique patients from appointments (via slots related to this doctor)
        // We use Eloquent to fetch distinct patients.
        // Logic: Get appointments where the related slot belongs to the authenticated doctor.
        $appointments = Appointment::whereHas('slot', function ($query) use ($doctor) {
            $query->where('user_id', $doctor->id);
        })
        ->with(['patient', 'slot']) // Eager load patient and slot to avoid N+1
        ->get();

        // 2. Group by patient_id to get unique patients
        // We also want to calculate stats: Last appointment, Total appointments.
        $patients = $appointments->groupBy('patient_id')->map(function ($patientAppointments) {
            $patient = $patientAppointments->first()->patient;
            $sorted = $patientAppointments->sortByDesc('slot.start_time');

            return [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'total_appointments' => $patientAppointments->count(),
                'last_appointment' => $sorted->first()->slot->start_time,
                'appointments' => $sorted, // Full history: desc order (last() gives oldest = "patient since")
            ];
        })->sortByDesc('last_appointment'); // Sort list by most recent interaction

        return view('doctor.patients.index', compact('patients'));
    }
}
