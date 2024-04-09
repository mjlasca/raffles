<?php

namespace App\Exports;

use App\Models\Outflow;
use Maatwebsite\Excel\Concerns\FromCollection;

class OutflowsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Outflow::all();
    }
}
