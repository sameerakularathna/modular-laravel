<?php
use Custom\Employee\Livewire\EmployeeIndex;
use Custom\Employee\Livewire\EmployeeCreate;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', EmployeeIndex::class)->name('employees.index');
        Route::get('/create', EmployeeCreate::class)->name('employees.create');
    });
});


