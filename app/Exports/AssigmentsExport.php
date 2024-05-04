<?php

namespace App\Exports;

use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssigmentsExport implements FromCollection, WithHeadings
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
        return Assignment::select(
            DB::raw('(SELECT name FROM raffles WHERE raffles.id = assignments.raffle_id) as raffle_name'),
            'tickets_numbers',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = assignments.user_id) as user'),
            'commission',
            'created_at',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = assignments.edit_user) as user_edit'),
        )->where('created_at','>=',$this->date1.' 00:00:00')->where('created_at','<=',$this->date2.' 23:59:59')->get();
    }

    public function headings(): array
    {
        return [
            'Rifa',
            'Tickets asignados',    
            'Usuario asignado',
            'Comisión asignada',
            'Fecha asinación',
            'Asignado por'
        ];
    }
}
