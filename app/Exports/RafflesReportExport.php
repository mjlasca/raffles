<?php

namespace App\Exports;

use App\Models\Raffle;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RafflesReportExport implements FromCollection, WithHeadings
{

    private $raffle;

    public function __construct(Request $req) {
        $this->raffle = $req->input('raffle_id');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ticket::where('tickets.raffle_id', $this->raffle)
        ->join('assignments', 'assignments.id', '=', 'tickets.assignment_id')
        ->selectRaw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = tickets.user_id) as user_id, 
                     COUNT(tickets.id) as total_tickets, 
                     COALESCE((SELECT SUM(deliveries.total) FROM deliveries WHERE deliveries.raffle_id = tickets.raffle_id AND deliveries.user_id = tickets.user_id), 0) as total_deliveries,
                     SUM(tickets.payment) as total_payment,
                     SUM(assignments.commission) as total_assignment, 
                     SUM(CASE WHEN tickets.payment = tickets.price THEN assignments.commission ELSE 0 END) as total_to_commission,
                     COALESCE((SELECT SUM(commissions.total) FROM commissions WHERE commissions.raffle_id = tickets.raffle_id AND commissions.user_id = tickets.user_id), 0) as total_commissions
                    ')
        ->groupBy('tickets.user_id')
        ->orderByDesc('total_tickets')
        ->get();
    }

    public function headings(): array
    {
        return [
            'Vendedor',
            '#Boletos',
            'Entregado',
            'Canjeado',
            'Comisión posible',
            'Comisión ganada',
            'Comisión liquidada',
        ];
    }
}
