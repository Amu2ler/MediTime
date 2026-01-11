<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();

        // Check authorization: must be the patient or the doctor linked to the slot
        if ($user->id !== $appointment->patient_id && $user->id !== $appointment->slot->user_id) {
            abort(403);
        }

        // Release the slot
        $appointment->slot->update(['is_booked' => false]);

        // Delete the appointment
        $appointment->delete();

        return redirect()->back()->with('success', 'Rendez-vous annulé avec succès.');
    }
}
