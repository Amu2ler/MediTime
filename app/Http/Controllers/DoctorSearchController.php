<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DoctorSearchController extends Controller
{
    public function index(Request $request)
    {
        $specialties = Specialty::orderBy('name')->get();

        $doctors = DoctorProfile::with(['user', 'specialty'])
            ->when($request->specialty_id, function (Builder $query, $specialtyId) {
                $query->where('specialty_id', $specialtyId);
            })
            ->when($request->city, function (Builder $query, $city) {
                $query->where('city', 'like', "%{$city}%");
            })
            ->when($request->availability, function (Builder $query, $availability) {
                $query->whereHas('user.slots', function (Builder $query) use ($availability) {
                    $query->where('is_booked', false)
                          ->where('start_time', '>', now()); // Global rule: must be in future

                    if ($availability === 'today') {
                        $query->whereDate('start_time', now()->toDateString());
                    } elseif ($availability === 'week') {
                        $query->whereBetween('start_time', [now(), now()->addWeek()]);
                    }
                });
            })
            ->with(['user', 'specialty', 'user.slots' => function($query) {
                $query->where('is_booked', false)
                      ->where('start_time', '>', now())
                      ->orderBy('start_time');
            }])
            ->get();

        return view('doctor.search.index', compact('specialties', 'doctors'));
    }
}
