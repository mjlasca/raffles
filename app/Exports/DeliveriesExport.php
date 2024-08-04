<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use function PHPUnit\Framework\returnSelf;

class DeliveriesExport implements FromCollection, WithHeadings
{
    private $date1;
    private $date2;
    private $seller;
    private $raffle;

    public function __construct($req) {
        $this->date1 = $req->input('date1');
        $this->date2 = $req->input('date2');
        $this->seller = $req->input('user_id');
        $this->raffle = $req->input('raffle_id');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $delivery = Delivery::select(
            'created_at',
            'description',
            DB::raw('(SELECT name FROM raffles WHERE raffles.id = deliveries.raffle_id) as raffle_name'),
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = deliveries.user_id) as user_id'),
            'total',
            'used',
            DB::raw('IF(status = 0, "Anulada", "Activa") as status'),
            );
            if(!empty($this->date1))
                $delivery->where('created_at','>=',$this->date1.' 00:00:00');
            if(!empty($this->date2))
                $delivery->where('created_at','<=',$this->date2.' 23:59:59');
            if(!empty($this->seller))
                $delivery->where('user_id',$this->seller);
            if(!empty($this->raffle))
                $delivery->where('raffle_id',$this->raffle);

        return $delivery->get();
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
            'Estado'
        ];
    }
}
