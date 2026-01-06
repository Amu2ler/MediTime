<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::resource('specialties', SpecialtyController::class);
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/profile/create', [DoctorProfileController::class, 'create'])->name('doctor.profile.create');
    Route::post('/doctor/profile', [DoctorProfileController::class, 'store'])->name('doctor.profile.store');
    Route::get('/doctor/profile/edit', [DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
    Route::put('/doctor/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');
});

require __DIR__.'/auth.php';
