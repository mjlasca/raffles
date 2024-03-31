<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
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
