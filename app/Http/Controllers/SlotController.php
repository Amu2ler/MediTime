<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slots = Slot::where('user_id', Auth::id())
            ->where('start_time', '>=', now())
            ->with('appointment.patient') // Eager load appointment and patient references
            ->orderBy('start_time')
            ->get();

        return view('doctor.slots.index', compact('slots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'duration' => ['nullable', 'integer', 'min:5', 'max:120'], // Duration in minutes for auto-generation
        ]);

        $date = $request->date;
        $start = Carbon::parse("$date {$request->start_time}");
        $end = Carbon::parse("$date {$request->end_time}");
        $duration = $request->duration ? (int)$request->duration : null;

        if ($duration) {
            // Generate multiple slots
            $current = $start->copy();
            while ($current->copy()->addMinutes($duration)->lte($end)) {
                Slot::create([
                    'user_id' => Auth::id(),
                    'start_time' => $current,
                    'end_time' => $current->copy()->addMinutes($duration),
                ]);
                $current->addMinutes($duration);
            }
        } else {
            // Create single slot
            Slot::create([
                'user_id' => Auth::id(),
                'start_time' => $start,
                'end_time' => $end,
            ]);
        }

        return redirect()->route('slots.index')->with('success', 'Créneaux ajoutés avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slot $slot)
    {
        if ($slot->user_id !== Auth::id()) {
            abort(403);
        }

        $slot->delete();

        return redirect()->route('slots.index')->with('success', 'Créneau supprimé.');
    }
}
