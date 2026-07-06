<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\BrowsePhotographers;
use App\Livewire\ReviewQuote;
use App\Livewire\ShowPhotographerProfile;
use App\Livewire\SubmitReview;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/photographers', BrowsePhotographers::class)->name('photographers.index');

Route::get('/quotes/{quote}', ReviewQuote::class)->name('quotes.show')->middleware('signed');
Route::get('/booking-requests/{bookingRequest}/review', SubmitReview::class)->name('reviews.create')->middleware('signed');

Route::get('/styleguide', function () {
    return view('styleguide');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{slug}', ShowPhotographerProfile::class)->name('photographers.show');
