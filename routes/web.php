<?php

use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ProposalReviewController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::resource('proposals', ProposalController::class);
    Route::post('proposals/{proposal}/reviews', [ProposalReviewController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('proposals.reviews.store');
});

require __DIR__.'/settings.php';
