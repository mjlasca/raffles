<?php

namespace App\Exports;

use App\Models\Assigment;
use Maatwebsite\Excel\Concerns\FromCollection;

class AssigmentsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Assigment::all();
    }
}
