<?php
use Illuminate\Support\Facades\Route;
use Custom\Sales\Livewire\SalesIndex;

Route::middleware(['web'])->group(function () {
    Route::get('/sales', SalesIndex::class)->name('sales.index');
});

