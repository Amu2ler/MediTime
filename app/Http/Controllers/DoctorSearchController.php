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
            ->get();

        return view('doctor.search.index', compact('specialties', 'doctors'));
    }
}
