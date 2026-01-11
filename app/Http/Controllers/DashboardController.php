<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // dd($user->role); // Uncomment to debug

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $appointments = collect();

        if ($user->role === 'doctor') {
            // Get appointments for this doctor (via slots)
            $appointments = Appointment::whereHas('slot', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['patient', 'slot'])
            ->get()
            ->sortBy('slot.start_time');
        } else {
            // Get appointments for this patient
            $appointments = Appointment::where('patient_id', $user->id)
                ->with(['slot.user.doctorProfile', 'slot.user']) // specialized load for doctor profile
                ->get()
                ->sortBy('slot.start_time');
        }

        return view('dashboard', compact('appointments'));
    }
}
