<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OutFlowController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\RaffleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes([
    'reset' => false, // Reset Password Routes...
    'verify' => false, // Email Verification Routes...
]);


Route::resource('rifas', RaffleController::class)->middleware(['auth',"roleAccess:Administrador"])->parameters([
    'rifas' => 'raffles',
]);
Route::get('/raffles/export', [RaffleController::class, 'export'])->middleware(['auth'])->name('rifas.export');

Route::resource('usuarios', UserController::class)->middleware(['auth','roleAccess:Secretaria-Administrador'])->parameters([
    'usuarios' => 'user',
]);

Route::get('/users/export', [UserController::class, 'export'])->middleware(['auth'])->name('usuarios.export');

Route::resource('asignaciones', AssignmentController::class)->middleware(['auth',"roleAccess:Secretaria-Administrador"])->parameters([
    'asignaciones' => 'assignment',
]);
Route::get('/assignments/export', [AssignmentController::class, 'export'])->middleware(['auth'])->name('asignaciones.export');

Route::resource('premios', PrizeController::class)->middleware(['auth','roleAccess:Secretaria-Administrador'])->parameters([
    'premios' => 'prize',
]);
Route::get('/prizes/export', [PrizeController::class, 'export'])->middleware(['auth'])->name('premios.export');

Route::resource('comisiones', CommissionController::class)->middleware(['auth','roleAccess:Secretaria-Administrador'])->parameters([
    'comisiones' => 'commission',
]);
Route::get('/commissions/export', [CommissionController::class, 'export'])->middleware(['auth'])->name('comisiones.export');

Route::resource('salidas', OutFlowController::class)->middleware(['auth','roleAccess:Secretaria-Administrador'])->parameters([
    'salidas' => 'outflow',
]);
Route::get('/outflows/export', [OutFlowController::class, 'export'])->middleware(['auth'])->name('salidas.export');

Route::resource('arqueos', CashController::class)->middleware(['auth'])->parameters([
    'arqueos' => 'cash',
]);
Route::get('/cashes/export', [CashController::class, 'export'])->middleware(['auth'])->name('arqueos.export');

Route::middleware(['auth'])->group(function () {
    Route::resource('boletas', TicketController::class)->parameters([
        'boletas' => 'ticket',
    ]);

    
});
Route::get('/tickets', function () {
    return redirect('/boletas');
});
Route::get('/tickets/export', [TicketController::class, 'export'])->middleware(['auth'])->name('boletas.export');

Route::get('/tickets/pago', [TicketController::class, 'pay'])->middleware(['auth'])->name('boletas.pay');
Route::get('/tickets/checkticket', [TicketController::class, 'checkticket'])->middleware(['auth'])->name('checkticket');
Route::post('/tickets/setpay', [TicketController::class, 'setpay'])->middleware(['auth'])->name('tickets.setpay');
Route::post('/tickets/payall', [TicketController::class, 'payall'])->middleware(['auth'])->name('tickets.payall');
Route::post('/tickets/removable', [TicketController::class, 'removable'])->middleware(['auth'])->name('tickets.removable');

Route::get('/resultados', [PrizeController::class, 'results'])->name('prizes.rasults');

Route::resource('entregas', DeliveryController::class)->middleware(['auth','roleAccess:Secretaria-Administrador'])->parameters([
    'entregas' => 'deliveries',
]);
Route::get('/entregas/pdf/{id}', [DeliveryController::class, 'pdf'])->middleware(['auth'])->name('entregas.pdf');
Route::get('/deliveries/export', [DeliveryController::class, 'export'])->middleware(['auth'])->name('entregas.export');
Route::get('/deliveries/payment/{id}', [DeliveryController::class, 'payment'])->middleware(['auth'])->name('entregas.payment');

//Route::view('/', 'dashboard')->middleware(['auth']);
Route::get('/', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
/*Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');*/


