<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select(
            'name',
            'lastname',
            'phone',
            'email',
            'address',
            'role',
            'created_at',
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Celular',
            'Correo electrónico',
            'Dirección',
            'Rol',
            'Fecha creación',
        ];
    }
}
