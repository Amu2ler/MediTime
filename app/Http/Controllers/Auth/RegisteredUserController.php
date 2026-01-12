<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Specialty;
use App\Models\DoctorProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'specialties' => Specialty::all(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:patient,doctor'], // Removed admin
            'specialty_id' => ['nullable', 'required_if:role,doctor', 'exists:specialties,id'],
            'certificate' => ['nullable', 'required_if:role,doctor', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user->role === 'doctor') {
            $certificatePath = null;
            if ($request->hasFile('certificate')) {
                $certificatePath = $request->file('certificate')->store('certificates', 'public');
            }

            DoctorProfile::create([
                'user_id' => $user->id,
                'specialty_id' => (int) $request->input('specialty_id'),
                'certificate_path' => $certificatePath,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
