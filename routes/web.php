<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ConsolidatedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Office;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\DeliveryPermission;
use App\Http\Controllers\DeliveryPermissionController;
use App\Http\Controllers\OutFlowController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\RaffleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Auth::routes([
    'reset' => false, // Reset Password Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/testzone', function () {
dd([
    'php_timezone' => date_default_timezone_get(),
    'laravel_now' => now(),
    'mysql_now' => DB::select("SELECT NOW() as now")[0]->now
]);

});

Route::resource('rifas', RaffleController::class)->middleware(['auth',"roleAccess:Administrador-Secretaria"])->parameters([
    'rifas' => 'raffles',
]);
Route::get('/raffles/export', [RaffleController::class, 'export'])->middleware(['auth'])->name('rifas.export');
Route::get('/raffles/export-cons', [RaffleController::class, 'exportRaffle'])->middleware(['auth'])->name('rifas.export_cons');

Route::resource('usuarios', UserController::class)->middleware(['auth','roleAccess:Secretaria-Administrador'])->parameters([
    'usuarios' => 'user',
]);

Route::get('/users/export', [UserController::class, 'export'])->middleware(['auth'])->name('usuarios.export');

Route::put('/users/{id}', [UserController::class, 'delete'])->middleware(['auth','roleAccess:Secretaria-Administrador'])->name('usuarios.delete');

Route::resource('asignaciones', AssignmentController::class)->middleware(['auth',"roleAccess:Secretaria-Administrador"])->parameters([
    'asignaciones' => 'assignment',
]);
Route::get('/assignments/export', [AssignmentController::class, 'export'])->middleware(['auth'])->name('asignaciones.export');
Route::get('/asignaciones/change/user', [AssignmentController::class, 'change'])->middleware(['auth'])->name('asignaciones.change');
Route::post('/asignaciones/change/user', [AssignmentController::class, 'change'])->middleware(['auth'])->name('asignaciones.change');

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
Route::get('/arqueos/dia/{date}', [CashController::class, 'dayView'])->middleware(['auth'])->name('arqueos.dayview');

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
Route::post('/tickets/pago', [TicketController::class, 'pay'])->middleware(['auth'])->name('boletas.pay');
Route::get('/tickets/checkticket', [TicketController::class, 'checkticket'])->middleware(['auth'])->name('checkticket');
Route::post('/tickets/setpay', [TicketController::class, 'setpay'])->middleware(['auth'])->name('tickets.setpay');
Route::post('/tickets/payall', [TicketController::class, 'payall'])->middleware(['auth'])->name('tickets.payall');
Route::post('/tickets/removable', [TicketController::class, 'removable'])->middleware(['auth'])->name('tickets.removable');

Route::get('/resultados', [PrizeController::class, 'results'])->name('prizes.results');

Route::resource('entregas', DeliveryController::class)->middleware(['auth','roleAccess:Secretaria-Administrador'])->parameters([
    'entregas' => 'deliveries',
]);

Route::get('/permisos-entregas', [DeliveryPermissionController::class, 'index'])->middleware(['auth'])->name('delivery_permission.index');
Route::get('/permisos-entregas/create/{delivery_id}', [DeliveryPermissionController::class, 'create'])->middleware(['auth'])->name('delivery_permission.create');
Route::post('/permisos-entregas/store', [DeliveryPermissionController::class, 'store'])->middleware(['auth'])->name('permisos-entregas.store');
Route::get('/permisos-entregas/{id}/edit', [DeliveryPermissionController::class, 'edit'])->middleware(['auth','roleAccess:Administrador'])->name('delivery_permission.edit');
Route::put('/permisos-entregas/{id}/update', [DeliveryPermissionController::class, 'update'])->middleware(['auth','roleAccess:Administrador'])->name('delivery_permission.update');
Route::get('/permisos-entregas/pending', [DeliveryPermissionController::class, 'pending'])->middleware(['auth'])->name('delivery_permission.pending');

Route::get('/entregas/pdf/{id}', [DeliveryController::class, 'pdf'])->middleware(['auth'])->name('entregas.pdf');
Route::get('/deliveries/export', [DeliveryController::class, 'export'])->middleware(['auth'])->name('entregas.export');
Route::get('/deliveries/payment/{id}', [DeliveryController::class, 'payment'])->middleware(['auth'])->name('entregas.payment');
Route::get('/deliveries/cancel/{id}', [DeliveryController::class, 'cancel'])->middleware(['auth','roleAccess:Secretaria-Administrador'])->name('entregas.cancel');
Route::get('/deliveries/proccess', [DeliveryController::class, 'proccess'])->middleware(['auth','roleAccess:Administrador'])->name('entregas.proccess');

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

//PaymentMethods
Route::get('/payment-method/', [PaymentMethodController::class, 'index'])->middleware(['auth','roleAccess:Secretaria-Administrador'])->name('paymentmethod.index');
Route::post('/payment-method/', [PaymentMethodController::class, 'store'])->middleware(['auth','roleAccess:Secretaria-Administrador'])->name('paymentmethod.index');

//Offices
Route::get('/oficinas/', [OfficeController::class, 'index'])->middleware(['auth','roleAccess:Secretaria-Administrador'])->name('office.index');
Route::post('/oficinas/', [OfficeController::class, 'store'])->middleware(['auth','roleAccess:Secretaria-Administrador'])->name('office.index');

//Consolidated
Route::get('/consolidado/', [ConsolidatedController::class, 'index'])->middleware(['auth','roleAccess:Secretaria-Administrador'])->name('consolidated.index');