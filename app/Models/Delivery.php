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
        'create_user',
        'edit_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }
}
