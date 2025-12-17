<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    public function create()
    {
        return view('doctor-profile.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        DoctorProfile::create([
            'user_id' => Auth::id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ]);

        return redirect()->route('dashboard');
    }

    public function edit()
    {
        $profile = Auth::user()->doctorProfile;
        return view('doctor-profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = Auth::user()->doctorProfile;

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $profile->update($request->all());

        return redirect()->route('dashboard');
    }
}
