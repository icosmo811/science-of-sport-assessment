<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicEntryController;
use Illuminate\Support\Facades\Route;

Route::redirect(
    '/',
    '/events/golf-classic-tournament-2025',
);

Route::get(
    '/events/{entry:slug}',
    PublicEntryController::class,
)->name('entries.public.show');

Route::redirect('/dashboard', '/entries')
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('entries', EntryController::class)->except('show');
});

require __DIR__.'/auth.php';
