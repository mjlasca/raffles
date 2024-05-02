<?php

namespace App\Exports;

use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssigmentsExport implements FromCollection, WithHeadings
{
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
            'updated_at',
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = assignments.edit_user) as user_edit'),
        )->get();
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
