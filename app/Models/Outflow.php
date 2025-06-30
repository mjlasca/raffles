<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail',
        'total',
        'raffle_id',
        'create_user',
        'edit_user',
        'payment_method_id',
        'office_id'
    ];

    public function rcreated()
    {
        return $this->belongsTo(User::class, 'create_user');
    }

    public function redited()
    {
        return $this->belongsTo(User::class, 'edit_user');
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class, 'raffle_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
