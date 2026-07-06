<?php

use App\Enums\UserRole;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Livewire\BrowsePhotographers;
use App\Livewire\MyBookingRequests;
use App\Livewire\PhotographerOnboarding;
use App\Livewire\ReviewQuote;
use App\Livewire\ShowMyBookingRequest;
use App\Livewire\ShowPhotographerProfile;
use App\Livewire\SubmitReview;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/photographers', BrowsePhotographers::class)->name('photographers.index');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/quotes/{quote}', ReviewQuote::class)->name('quotes.show')->middleware('signed');
Route::get('/booking-requests/{bookingRequest}/review', SubmitReview::class)->name('reviews.create')->middleware('signed');

Route::get('/styleguide', function () {
    return view('styleguide');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-bookings', MyBookingRequests::class)->name('bookings.index');
    Route::get('/my-bookings/{bookingRequest}', ShowMyBookingRequest::class)->name('bookings.show');

    Route::get('/photographer/onboarding', PhotographerOnboarding::class)
        ->name('photographer.onboarding');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            UserRole::Photographer => $user->profile
                ? redirect('/photographer')
                : redirect()->route('photographer.onboarding'),
            UserRole::Admin => redirect('/admin'),
            default => redirect()->route('bookings.index'),
        };
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{slug}', ShowPhotographerProfile::class)->name('photographers.show');
