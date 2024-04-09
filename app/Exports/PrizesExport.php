<?php

namespace App\Exports;

use App\Models\Prize;
use Maatwebsite\Excel\Concerns\FromCollection;

class PrizesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Prize::all();
    }
}
