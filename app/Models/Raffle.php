<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description',
        'price',
        'winning_ticket_id',
        'raffle_date',
        'tickets_number',
        'ticket_commission',
        'raffle_status',
        'status',
        'create_user',
        'edit_user',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function winningTicket()
    {
        return $this->belongsTo(Ticket::class, 'winning_ticket_id');
    }

    public function prizes()
    {
        return $this->hasMany(Prize::class);
    }
}
