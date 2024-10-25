<?php

namespace App\Exports;

use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

class TicketsExport implements FromCollection, WithHeadings, WithEvents
{
    private $raffleId;
    private $userId;

    public function __construct($req) {
        $this->raffleId = $req->input('raffle_id');
        $this->userId = $req->input('user_id');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $tickets = Ticket::select(
            DB::raw(' IF( removable = 0 , "NO","SI")'),
            DB::raw('(SELECT name FROM raffles WHERE raffles.id = tickets.raffle_id) as raffle_name'),
            'ticket_number',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = tickets.user_id) as user'),
            'price',
            'payment',
            DB::raw('(SELECT total FROM commissions WHERE commissions.id = tickets.payment_commission) as payment_commission'),
            'customer_name',
            'customer_phone',
            'movements'
        );

        if(!empty($this->raffleId))
            $tickets = $tickets->where('raffle_id',$this->raffleId);
        
        if(!empty($this->userId))
            $tickets = $tickets->where('user_id',$this->userId);

        return $tickets->orderBy('ticket_number','ASC')->get();
    }

    public function headings(): array
    {
        return [
            'Desprendible',
            'Rifa',    
            'Boleta',
            'Vendedor(a)',
            'Valor',
            'Abonado',
            'Comisión',
            'Nombre cliente',
            'Teléfono cliente',
            'Movimientos del ticket'
        ];
    }

    public function registerEvents(): array
    {
        if(!empty($this->userId) && !empty($this->raffleId)){
            return [
                BeforeSheet::class => function(BeforeSheet $event) {
                    $sumPrice = Ticket::where('raffle_id',$this->raffleId)->where('user_id',$this->userId)->sum('price');
                    $sumPayment = Ticket::where('raffle_id',$this->raffleId)->where('user_id',$this->userId)->sum('payment');
                    $countTicket = Ticket::where('raffle_id',$this->raffleId)->where('user_id',$this->userId)->count();
                    $raffle = Raffle::find($this->raffleId);
                    $user = User::find($this->userId);
                    $sheet = $event->sheet->getDelegate();
                    $sheet->setCellValue('A1', 'ESTADO DE CUENTA');
                    $sheet->setCellValue('A2', 'Fecha generación: ' . now()->format('Y-m-d'));
                    $sheet->setCellValue('F1', 'Total rifa');
                    $sheet->setCellValue('G1', $sumPrice);
                    $sheet->setCellValue('F2', 'Abonado total');
                    $sheet->setCellValue('G2', $sumPayment);
                    $sheet->setCellValue('F3', 'Saldo pendiente');
                    $sheet->setCellValue('G3', ($sumPrice - $sumPayment));
                    $sheet->setCellValue('A3', 'Rifa:  ' . $raffle->name);
                    $sheet->setCellValue('C3', 'No. Boletas:  ' . $countTicket);
                    $sheet->setCellValue('A4', 'Vendedor(a): ' . $user->name." ".$user->lastname);
                },
            ];
        }elseif(!empty($this->raffleId)){
            return [
                BeforeSheet::class => function(BeforeSheet $event) {
                    $sumPrice = Ticket::where('raffle_id',$this->raffleId)->sum('price');
                    $sumPayment = Ticket::where('raffle_id',$this->raffleId)->sum('payment');
                    $countTicket = Ticket::where('raffle_id',$this->raffleId)->count();
                    $raffle = Raffle::find($this->raffleId);
                    $sheet = $event->sheet->getDelegate();
                    $sheet->setCellValue('A1', 'ESTADO DE CUENTA');
                    $sheet->setCellValue('A2', 'Fecha generación: ' . now()->format('Y-m-d'));
                    $sheet->setCellValue('F1', 'Total rifa');
                    $sheet->setCellValue('G1', $sumPrice);
                    $sheet->setCellValue('F2', 'Abonado total');
                    $sheet->setCellValue('G2', $sumPayment);
                    $sheet->setCellValue('F3', 'Saldo pendiente');
                    $sheet->setCellValue('G3', ($sumPrice - $sumPayment));
                    $sheet->setCellValue('A3', 'Rifa:  ' . $raffle->name);
                    $sheet->setCellValue('C3', 'No. Boletas:  ' . $countTicket);
                },
            ];
        }elseif(!empty($this->userId)){
            return [
                BeforeSheet::class => function(BeforeSheet $event) {
                    $sumPrice = Ticket::where('user_id',$this->userId)->sum('price');
                    $sumPayment = Ticket::where('user_id',$this->userId)->sum('payment');
                    $countTicket = Ticket::where('user_id',$this->userId)->count();
                    $user = User::find($this->userId);
                    $sheet = $event->sheet->getDelegate();
                    $sheet->setCellValue('A1', 'ESTADO DE CUENTA');
                    $sheet->setCellValue('A2', 'Fecha generación: ' . now()->format('Y-m-d'));
                    $sheet->setCellValue('F1', 'Total rifas');
                    $sheet->setCellValue('G1', $sumPrice);
                    $sheet->setCellValue('F2', 'Abonado total');
                    $sheet->setCellValue('G2', $sumPayment);
                    $sheet->setCellValue('F3', 'Saldo pendiente');
                    $sheet->setCellValue('G3', ($sumPrice - $sumPayment));
                    $sheet->setCellValue('A3', 'No. Boletas:  ' . $countTicket);
                    $sheet->setCellValue('A4', 'Vendedor(a): ' . $user->name." ".$user->lastname);
                },
            ];
        }else{
            return [];
        }

        
    }
}
