<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'raffle_id',
        'tickets_numbers',
        'tickets_total',
        'create_user',
        'edit_user',
        'user_referred',
        'commission'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'user_referred');
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function assignments()
    {
        return $this->hasMany(Ticket::class);
    }
}
