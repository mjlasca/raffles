<?php

namespace App\Exports;

use App\Models\Delivery;
use Maatwebsite\Excel\Concerns\FromCollection;

class DeliveriesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Delivery::all();
    }
}
