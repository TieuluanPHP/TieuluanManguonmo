<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventModerationController;
use App\Http\Controllers\Admin\OrganizerModerationController;
use App\Http\Controllers\Admin\TaxonomyController;
use App\Http\Controllers\Admin\WithdrawalModerationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\FinanceController as OrganizerFinanceController;
use App\Http\Controllers\OrganizerRequestController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{slug}', [EventDetailController::class, 'show'])->name('events.show');

// AJAX API for Seat & Dropdown Selector
Route::get('/api/events/{id}/ticket-types', [EventDetailController::class, 'apiTicketTypes'])->name('api.ticket-types');
Route::get('/api/ticket-types/{id}/available-rows', [EventDetailController::class, 'apiAvailableRows'])->name('api.available-rows');
Route::get('/api/ticket-types/{id}/rows/{row}/available-seats', [EventDetailController::class, 'apiAvailableSeats'])->name('api.available-seats');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Customer & Global Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Wallet & Mock Sandbox
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');

    // Booking & Tickets
    Route::post('/booking/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');

    // Partner Onboarding Request
    Route::get('/become-organizer', [OrganizerRequestController::class, 'show'])->name('organizer.request');
    Route::post('/become-organizer', [OrganizerRequestController::class, 'submit'])->name('organizer.request.post');
});

/*
|--------------------------------------------------------------------------
| Organizer Portal Routes (Role: organizer, admin)
|--------------------------------------------------------------------------
*/
Route::prefix('organizer')
    ->middleware(['auth', 'role:organizer,admin'])
    ->name('organizer.')
    ->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');

        // Concert creation & editing
        Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('events.create');
        Route::post('/events', [OrganizerEventController::class, 'store'])->name('events.store');
        Route::get('/events/{id}/edit', [OrganizerEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{id}', [OrganizerEventController::class, 'update'])->name('events.update');

        // Finance & Payout Withdrawals
        Route::get('/finance', [OrganizerFinanceController::class, 'index'])->name('finance');
        Route::post('/withdraw', [OrganizerFinanceController::class, 'requestWithdraw'])->name('withdraw.post');
    });

/*
|--------------------------------------------------------------------------
| Super Admin Portal Routes (Role: admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Organizer partner applications moderation
        Route::get('/organizers', [OrganizerModerationController::class, 'index'])->name('organizers.index');
        Route::post('/organizers/{id}/approve', [OrganizerModerationController::class, 'approve'])->name('organizers.approve');
        Route::post('/organizers/{id}/reject', [OrganizerModerationController::class, 'reject'])->name('organizers.reject');

        // Event moderation & cancellation auto-refund
        Route::get('/events', [EventModerationController::class, 'index'])->name('events.index');
        Route::get('/events/{id}/preview', [EventModerationController::class, 'preview'])->name('events.preview');
        Route::post('/events/{id}/approve', [EventModerationController::class, 'approve'])->name('events.approve');
        Route::post('/events/{id}/reject', [EventModerationController::class, 'reject'])->name('events.reject');
        Route::post('/events/{id}/cancel', [EventModerationController::class, 'cancel'])->name('events.cancel');

        // Withdrawal payouts moderation
        Route::get('/withdrawals', [WithdrawalModerationController::class, 'index'])->name('withdrawals.index');
        Route::post('/withdrawals/{id}/approve', [WithdrawalModerationController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{id}/reject', [WithdrawalModerationController::class, 'reject'])->name('withdrawals.reject');

        // System Taxonomies (Categories, Venues, Artists)
        Route::get('/taxonomies', [TaxonomyController::class, 'index'])->name('taxonomies.index');
        Route::post('/taxonomies/category', [TaxonomyController::class, 'storeCategory'])->name('taxonomies.category.store');
        Route::post('/taxonomies/venue', [TaxonomyController::class, 'storeVenue'])->name('taxonomies.venue.store');
        Route::post('/taxonomies/artist', [TaxonomyController::class, 'storeArtist'])->name('taxonomies.artist.store');
    });
