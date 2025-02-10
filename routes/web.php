<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PackageManager1;
use App\Livewire\PackageInstaller1;
Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
// Route for the Package Manager
Route::get('/package-manager', PackageManager1::class)->name('package.manager');
Route::get('/package-installer', PackageInstaller1::class)->name('package.installer');

});

require __DIR__.'/auth.php';
