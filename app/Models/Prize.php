<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

    protected $fillable = [
        'raffle_id', 
        'winning_ticket',
        'award_date',
        'status',
        'create_user',
        'detail',
        'percentage_condition',
        'edit_user',
    ];

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function rcreated()
    {
        return $this->belongsTo(User::class, 'create_user');
    }

    public function redited()
    {
        return $this->belongsTo(User::class, 'edit_user');
    }
}
