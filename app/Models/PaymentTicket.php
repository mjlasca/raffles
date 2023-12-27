<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'payment_value',
        'status',
        'detail',
        'create_user',
        'edit_user',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

}
