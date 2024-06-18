<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_date', 
        'real_money_box',
        'manual_money_box',
        'difference',
        'deliveries',
        'day_outings',
        'create_user',
        'edit_user',
    ];

    public function rcreated()
    {
        return $this->belongsTo(User::class, 'create_user');
    }

    public function redited()
    {
        return $this->belongsTo(User::class, 'edit_user');
    }
}
