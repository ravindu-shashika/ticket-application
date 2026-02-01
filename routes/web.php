<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TicketController::class, 'index'])->name('home');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::match(['get', 'post'], '/tickets/check', [TicketController::class, 'getStatus'])->name('tickets.status');


Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/testmail', [AgentController::class, 'testmail'])->name('agent.testmail');


Route::middleware(['auth'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets', [AgentController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/{id}', [AgentController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [AgentController::class, 'reply'])->name('tickets.reply');
});
