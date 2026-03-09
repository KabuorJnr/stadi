<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\TicketViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — PWA (fan-facing) & Admin Dashboard
|--------------------------------------------------------------------------
*/

// ─── PWA Public Routes ──────────────────────────────────
Route::get('/', [TicketViewController::class, 'events'])->name('home');
Route::get('/events/{event}/sections', [TicketViewController::class, 'selectSection'])->name('event.sections');
Route::get('/events/{event}/buy/{section}', [TicketViewController::class, 'buy'])->name('ticket.buy');
Route::get('/ticket/{qrHash}', [TicketViewController::class, 'show'])->name('ticket.show');

// ─── Admin Routes ───────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/revenue', [DashboardController::class, 'revenue'])->name('revenue');
        Route::get('/events/{event}/stats', [DashboardController::class, 'event'])->name('event.show');

        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::post('/events/{event}/toggle-sales', [EventController::class, 'toggleSales'])->name('events.toggle-sales');
    });
