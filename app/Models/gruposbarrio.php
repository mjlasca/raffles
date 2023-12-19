<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\barrio;

class gruposbarrio extends Model
{
    use HasFactory;

    
    public function barrio(){
        return $this->belongsTo(barrio::class);
    }
}
