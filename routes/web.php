<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\RaffleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes([
    'reset' => false, // Reset Password Routes...
    'verify' => false, // Email Verification Routes...
]);


Route::resource('rifas', RaffleController::class)->middleware(['auth'])->parameters([
    'rifas' => 'raffles',
]);
Route::resource('usuarios', UserController::class)->middleware(['auth'])->parameters([
    'usuarios' => 'user',
]);

Route::resource('asignaciones', AssignmentController::class)->middleware(['auth'])->parameters([
    'asignaciones' => 'assignment',
]);

Route::resource('boletas', TicketController::class)->middleware(['auth'])->parameters([
    'boletas' => 'ticket',
]);

Route::get('/tickets/pago', [TicketController::class, 'pay'])->middleware(['auth'])->name('boletas.pay');
Route::post('/tickets/setpay', [TicketController::class, 'pay'])->middleware(['auth'])->name('tickets.setpay');

Route::resource('entregas', DeliveryController::class)->middleware(['auth'])->parameters([
    'entregas' => 'deliveries',
]);

Route::view('/', 'dashboard')->middleware(['auth']);



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'], ['middleware' => ['auth', 'role:admin']])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


