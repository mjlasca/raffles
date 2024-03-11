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
        'create_user',
        'edit_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment_commissions()
    {
        return $this->hasMany(Ticket::class);
    }
}
