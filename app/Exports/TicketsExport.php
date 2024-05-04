<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection, WithHeadings
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
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = tickets.edit_user) as user_edit'),
            'price',
            'payment',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = tickets.user_id) as user'),
            DB::raw('(SELECT total FROM commissions WHERE commissions.id = tickets.payment_commission) as payment_commission'),
            'customer_name',
            'customer_phone',
            'movements'
        );

        if(!empty($this->raffleId))
            $tickets = $tickets->where('raffle_id',$this->raffleId);
        
        if(!empty($this->userId))
            $tickets = $tickets->where('user_id',$this->userId);

        return $tickets->get();
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
            'Nombre cliente',
            'Comisión',
            'Nombre cliente',
            'Teléfono cliente',
            'Movimientos del ticket'
        ];
    }
}
