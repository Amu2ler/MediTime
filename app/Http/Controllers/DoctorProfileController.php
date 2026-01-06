<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    public function create()
    {
        $specialties = Specialty::orderBy('name')->get();
        return view('doctor-profile.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'bio'          => ['nullable', 'string'],
        ]);

        DoctorProfile::create([
            'user_id'       => Auth::id(),
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'specialty_id'  => $request->specialty_id,
            'phone'         => $request->phone,
            'bio'           => $request->bio,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil médecin créé.');
    }

    public function edit()
    {
        $profile = Auth::user()->doctorProfile;

        if (!$profile) {
            return redirect()->route('doctor.profile.create');
        }

        $specialties = Specialty::orderBy('name')->get();
        return view('doctor-profile.edit', compact('profile', 'specialties'));
    }

    public function update(Request $request)
    {
        $profile = Auth::user()->doctorProfile;

        if (!$profile) {
            abort(404);
        }

        $request->validate([
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'bio'          => ['nullable', 'string'],
        ]);

        $profile->update([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'specialty_id' => $request->specialty_id,
            'phone'        => $request->phone,
            'bio'          => $request->bio,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil médecin mis à jour.');
    }
}
