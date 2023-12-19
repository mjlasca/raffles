<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\gruposbarrio;

class barrio extends Model
{
    use HasFactory;

    public function gruposbarrios(){
        return $this->hasMany(gruposbarrio::class);
    }
}
