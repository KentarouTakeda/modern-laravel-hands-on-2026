<?php

use App\Http\Controllers\ProposalController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::resource('proposals', ProposalController::class);
});

require __DIR__.'/settings.php';
