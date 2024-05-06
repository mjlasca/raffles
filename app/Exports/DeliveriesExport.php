<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliveriesExport implements FromCollection, WithHeadings
{
    private $date1;
    private $date2;

    public function __construct($req) {
        $this->date1 = $req->input('date1');
        $this->date2 = $req->input('date2');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Delivery::select(
            'updated_at',
            'description',
            DB::raw('(SELECT name FROM raffles WHERE raffles.id = deliveries.raffle_id) as raffle_name'),
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = deliveries.user_id) as user_id'),
            'total',
            'used',
            )->where('updated_at','>=',$this->date1.' 00:00:00')->where('updated_at','<=',$this->date2.' 23:59:59')->get();

        
    }

    public function headings(): array
    {
        return [
            'Fecha registro',
            'Descripción',    
            'Rifa',
            'Vendedor(a)',
            'Total',
            'Canjeado',
        ];
    }
}
