<?php

namespace App\Exports;

use App\Models\Outflow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OutflowsExport implements FromCollection, WithHeadings
{
    private $date1;
    private $date2;
    private $raffle_id;
    private $payment_method_id;

    public function __construct($req) {
        $this->date1 = $req->input('date1');
        $this->date2 = $req->input('date2');
        $this->raffle_id = $req->input('raffle_id');
        $this->payment_method_id = $req->input('payment_method_id');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $outflows =  Outflow::select(
            'updated_at',
            DB::raw('(SELECT name FROM raffles WHERE raffles.id = outflows.raffle_id) as raffle_name'),
            'detail',
            DB::raw('(SELECT description FROM payment_methods WHERE payment_methods.id = outflows.payment_method_id) as payment_method'),
            DB::raw('(SELECT CONCAT(name," ",lastname) FROM users WHERE users.id = outflows.edit_user) as edit_user'),
        );
        if($this->raffle_id)
            $outflows = $outflows->where('raffle_id',$this->raffle_id);
        if($this->date1)
            $outflows = $outflows->where('created_at','>=',$this->date1.' 00:00:00');
        if($this->date2)
            $outflows = $outflows->where('created_at','<=',$this->date2.' 23:59:59');
        if(!empty($this->payment_method_id))
            $outflows = $outflows->where('payment_method_id',$this->payment_method_id);


        return $outflows->get();
    }

    public function headings(): array
    {
        return [
            'Fecha salida',
            'Rifa',
            'Detalle',
            'Método de pago',
            'Editado por'
        ];
    }
}
