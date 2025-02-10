<?php
use Custom\Student\Livewire\StudentIndex;
use Custom\Student\Livewire\StudentCreate;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::group(['prefix' => 'students'], function () {
        Route::get('/', StudentIndex::class)->name('students.index');
        Route::get('/create', StudentCreate::class)->name('students.create');
    });
});


