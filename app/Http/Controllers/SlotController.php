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
    public function index(Request $request)
    {
        // Default to start of current week if no date provided
        $startOfWeek = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfWeek() 
            : now()->startOfWeek();
            
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        // Navigation dates
        $previousWeek = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $startOfWeek->copy()->addWeek()->format('Y-m-d');
        
        // Label (e.g., "15 Janvier - 21 Janvier 2024")
        $weekLabel = $startOfWeek->translatedFormat('d F') . ' - ' . $endOfWeek->translatedFormat('d F Y');

        $slots = Slot::where('user_id', Auth::id())
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->with('appointment.patient')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function($slot) {
                // Group by date (Y-m-d) for easier view rendering
                return $slot->start_time->format('Y-m-d');
            });

        return view('doctor.slots.index', compact('slots', 'startOfWeek', 'previousWeek', 'nextWeek', 'weekLabel'));
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
