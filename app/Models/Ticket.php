<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'title',
        'user_id',
        'description',
        'payment',
        'raffle_id',
        'create_user',
        'edit_user',
        'customer_name',
        'movements',
        'customer_phone',
        'assignment_id',
        'removable'
    ];

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function commission()
    {
        return $this->belongsTo(Commissions::class);
    }
}
