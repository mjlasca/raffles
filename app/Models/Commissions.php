<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commissions extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'percentage',
        'val_commission',
        'total',
        'detail',
        'raffle_id',
        'create_user',
        'edit_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'payment_commission');
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

}
