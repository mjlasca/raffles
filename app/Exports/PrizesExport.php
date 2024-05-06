<?php

namespace App\Exports;

use App\Models\Prize;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrizesExport implements FromCollection, WithHeadings
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
        return Prize::select(
            'type',
            DB::raw('(SELECT name FROM raffles WHERE raffles.id = prizes.raffle_id) as raffle_name'),
            'percentage_condition',
            'detail',
            'award_date',
            'updated_at',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = prizes.edit_user) as edit_user'),
            )->where('created_at','>=',$this->date1.' 00:00:00')->where('created_at','<=',$this->date2.' 23:59:59')->get();

        
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Rifa',    
            'Participa con',
            'Detalle',
            'Vendedor(a)',
            'Fecha sorteo',
            'Fecha edición',
            'Editado por'
        ];
    }
}
