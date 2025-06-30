<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'create_user', 'edit_user'];
    
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function outflows()
    {
        return $this->hasMany(Outflow::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commissions::class);
    }
}
