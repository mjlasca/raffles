<?php

namespace App\Exports;

use App\Models\Raffle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RafflesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Raffle::select(
            'name',
            'description',
            'price',
            DB::raw('DATE(raffle_date)'),
            'tickets_number',
            DB::raw('DATE(created_at)')
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Detalle',
            'Precio',
            'Fecha sorteo',
            'Cantidad de boletas',
            'Fecha creación',
        ];
    }
}
