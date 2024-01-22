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

Route::resource('premios', AssignmentController::class)->middleware(['auth'])->parameters([
    'premios' => 'prize',
]);

Route::middleware(['auth'])->group(function () {
    Route::resource('boletas', TicketController::class)->parameters([
        'boletas' => 'ticket',
    ]);

    
});

Route::get('/tickets/pago', [TicketController::class, 'pay'])->middleware(['auth'])->name('boletas.pay');
Route::get('/tickets/checkticket', [TicketController::class, 'checkticket'])->middleware(['auth'])->name('checkticket');
Route::post('/tickets/setpay', [TicketController::class, 'setpay'])->middleware(['auth'])->name('tickets.setpay');


Route::resource('entregas', DeliveryController::class)->middleware(['auth'])->parameters([
    'entregas' => 'deliveries',
]);
Route::get('/entregas/pdf/{id}', [DeliveryController::class, 'pdf'])->middleware(['auth'])->name('entregas.pdf');

Route::view('/', 'dashboard')->middleware(['auth']);



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'], ['middleware' => ['auth', 'role:admin']])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


