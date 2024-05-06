<?php

namespace App\Exports;


use App\Models\Commissions;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CommissionsExport implements FromCollection, WithHeadings
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
        return Commissions::select(
            'updated_at',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = commissions.user_id) as user_id'),
            'total',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = commissions.edit_user) as edit_user'),
            )->where('created_at','>=',$this->date1.' 00:00:00')->where('created_at','<=',$this->date2.' 23:59:59')->get();

        
    }

    public function headings(): array
    {
        return [
            'Fecha comisión',
            'Vendedor(a)',    
            'Total liquidado',
            'Editado por',
        ];
    }
}
