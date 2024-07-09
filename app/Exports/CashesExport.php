<?php

namespace App\Exports;

use App\Models\Cash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashesExport implements FromCollection, WithHeadings
{
    private $date1;
    private $date2;
    private $raffle_id;

    public function __construct($req) {
        $this->date1 = $req->input('date1');
        $this->date2 = $req->input('date2');
        $this->raffle_id = $req->input('raffle_id');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Cash::select(
            'updated_at',
            'day_date',
            'real_money_box',
            'manual_money_box',
            'difference',
            'deliveries',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = cashes.edit_user) as edit_user'),
            )->where('day_date','>=',$this->date1)->where('day_date','<=',$this->date2)->get();

        
    }

    public function headings(): array
    {
        return [
            'Fecha registro',
            'Día arqueo',    
            'Dinero real en caja',
            'Dinero manual ingresado',
            'Diferencia',
            'Entregas',
            'Editado por'
        ];
    }
}
