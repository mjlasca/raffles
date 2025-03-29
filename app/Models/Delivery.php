<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'raffle_id',
        'total',
        'description',
        'status',
        'used',
        'create_user',
        'edit_user',
        'consecutive',
        'payment_method_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rcreate()
    {
        return $this->belongsTo(User::class, 'create_user');
    }

    public function redited()
    {
        return $this->belongsTo(User::class, 'edit_user');
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
