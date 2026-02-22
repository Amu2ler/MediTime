<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'doctors_count' => User::where('role', 'doctor')->count(),
            'patients_count' => User::where('role', 'patient')->count(),
            'appointments_count' => Appointment::count(),
        ];

        // Chart Data: Appointments scheduled for the current week
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $appointments = Appointment::whereHas('slot', function ($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('start_time', [$startOfWeek, $endOfWeek]);
        })->with('slot')->get();

        $chartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $chartData['labels'][] = ucfirst($date->translatedFormat('l d/m')); // Lundi 19/01
            
            $count = $appointments->filter(function ($appt) use ($date) {
                return $appt->slot->start_time->isSameDay($date);
            })->count();
            
            $chartData['data'][] = $count;
        }

        return view('admin.dashboard', compact('stats', 'chartData'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        // 1. Search by Name or Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Date (Registration)
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 3. Filter by Role
        if ($request->role && in_array($request->role, ['doctor', 'patient'])) {
            $query->where('role', $request->role);
        }

        // 4. Sorting
        $allowedSorts = ['name', 'created_at', 'email', 'role'];
        $sortBy = in_array($request->sort_by, $allowedSorts) ? $request->sort_by : 'created_at';
        $sortOrder = $request->sort_order === 'asc' ? 'asc' : 'desc';

        $users = $query->orderBy($sortBy, $sortOrder)
                       ->with('doctorProfile') // Load profile for address data
                       ->paginate(20)
                       ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Authorization: Cannot edit admins
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de modifier un administrateur.');
        }

        // 2. Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:doctor,patient',
            'address' => 'required_if:role,doctor|string|max:255',
            'city' => 'required_if:role,doctor|string|max:255',
            'zip_code' => 'required_if:role,doctor|string|max:20',
        ]);

        // 3. Update User
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // 4. Update Doctor Profile
        if ($validated['role'] === 'doctor') {
            $user->doctorProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'zip_code' => $validated['zip_code'],
                ]
            );
        }

        return back()->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroyUser(User $user)
    {
        // Prevent deleting self
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Constraint: Doctor with future appointments
        if ($user->role === 'doctor') {
            $futureAppointments = $user->slots()
                ->where('start_time', '>', now())
                ->where('is_booked', true)
                ->count();

            if ($futureAppointments > 0) {
                return back()->with('error', "Impossible de supprimer ce médecin car il a {$futureAppointments} rendez-vous futurs planifiés.");
            }
        }

        // Constraint: Patient with future appointments
        if ($user->role === 'patient') {
            $futureAppointments = Appointment::where('patient_id', $user->id)
                ->whereHas('slot', function ($query) {
                    $query->where('start_time', '>', now());
                })
                ->count();

            if ($futureAppointments > 0) {
                return back()->with('error', "Impossible de supprimer ce patient car il a {$futureAppointments} rendez-vous futurs planifiés.");
            }
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
