<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DoctorSearchController;

use App\Models\Specialty;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome', [
        'specialties' => Specialty::orderBy('name')->get()
    ]);
});

Route::get('/search', [DoctorSearchController::class, 'index'])->name('doctor.search');


Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/booking/{doctor}', [\App\Http\Controllers\BookingController::class, 'create'])->name('patient.booking.create');
    Route::post('/booking', [\App\Http\Controllers\BookingController::class, 'store'])->name('patient.booking.store');
    
    Route::delete('/appointments/{appointment}', [\App\Http\Controllers\AppointmentController::class, 'destroy'])->name('appointments.destroy');
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/profile/create', [DoctorProfileController::class, 'create'])->name('doctor.profile.create');
    Route::post('/doctor/profile', [DoctorProfileController::class, 'store'])->name('doctor.profile.store');

    Route::get('/doctor/profile/edit', [DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
    Route::put('/doctor/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');

    Route::resource('doctor/slots', \App\Http\Controllers\SlotController::class);
    Route::get('/doctor/patients', [\App\Http\Controllers\DoctorPatientController::class, 'index'])->name('doctor.patients.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admin.users.update');
    
    // Moved Specialty management to Admin
    Route::resource('specialties', SpecialtyController::class);
});



require __DIR__.'/auth.php';
