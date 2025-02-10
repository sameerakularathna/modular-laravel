<?php
use Illuminate\Support\Facades\Route;
use Custom\GoldRate\Livewire\GoldRateComponent;

Route::middleware(['web'])->group(function () {
Route::get('/gold-rate', GoldRateComponent::class)->name('gold.rate');
});


