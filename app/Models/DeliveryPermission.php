<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Delivery;

class DeliveryPermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_requests',
        'delivery_id',
        'allow_user',
        'status',
        'date_permission'
    ];

    public function urequests()
    {
        return $this->belongsTo(User::class, 'user_requests');
    }

    public function uallow()
    {
        return $this->belongsTo(User::class, 'allow_user');
    }

    public function deliveries()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }
}
