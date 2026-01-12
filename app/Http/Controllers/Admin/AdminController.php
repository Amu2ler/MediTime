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

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        // 1. Filter by Role
        if ($request->role && in_array($request->role, ['doctor', 'patient'])) {
            $query->where('role', $request->role);
        }

        // 2. Sorting
        $allowedSorts = ['name', 'created_at'];
        $sortBy = in_array($request->sort_by, $allowedSorts) ? $request->sort_by : 'created_at';
        $sortOrder = $request->sort_order === 'asc' ? 'asc' : 'desc';

        $users = $query->orderBy($sortBy, $sortOrder)
                       ->paginate(20)
                       ->withQueryString();

        return view('admin.users.index', compact('users'));
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
