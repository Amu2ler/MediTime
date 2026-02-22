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
        return view('doctor.profile.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'specialty_id' => ['required', 'exists:specialties,id'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'bio'          => ['nullable', 'string'],
            'address'      => ['nullable', 'string', 'max:255'],
            'city'         => ['nullable', 'string', 'max:255'],
            'zip_code'     => ['nullable', 'string', 'max:10'],
        ]);

        DoctorProfile::create([
            'user_id'       => Auth::id(),
            'specialty_id'  => $request->specialty_id,
            'phone'         => $request->phone,
            'bio'           => $request->bio,
            'address'       => $request->address,
            'city'          => $request->city,
            'zip_code'      => $request->zip_code,
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
        return view('doctor.profile.edit', compact('profile', 'specialties'));
    }

    public function update(Request $request)
    {
        $profile = Auth::user()->doctorProfile;

        if (!$profile) {
            abort(404);
        }

        $request->validate([
            'specialty_id' => ['required', 'exists:specialties,id'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'bio'          => ['nullable', 'string'],
            'address'      => ['nullable', 'string', 'max:255'],
            'city'         => ['nullable', 'string', 'max:255'],
            'zip_code'     => ['nullable', 'string', 'max:10'],
            'certificate'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $data = [
            'specialty_id' => $request->specialty_id,
            'phone'        => $request->phone,
            'bio'          => $request->bio,
            'address'      => $request->address,
            'city'         => $request->city,
            'zip_code'     => $request->zip_code,
        ];

        // Check if specialty changes, require valid certificate if not provided, or invalidate profile if logic demands
        if ($profile->specialty_id != $request->specialty_id) {
            if (!$request->hasFile('certificate')) {
                 // In a real scenario, we might force them to upload it.
                 // For now, let's assume they MUST upload it if they change specialty.
                 $request->validate([
                    'certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
                 ], ['certificate.required' => 'Vous devez déposer une nouvelle attestation si vous changez de spécialité.']);
            }
        }

        if ($request->hasFile('certificate')) {
            // Delete old if exists (optional cleanup)
            // Store new
            $data['certificate_path'] = $request->file('certificate')->store('certificates', 'public');
        }

        $profile->update($data);

        return redirect()->route('doctor.profile.edit')->with('status', 'doctor-profile-updated');
    }
}
