<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingDuplicate extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefijo',
        'idpropuesta',
        'meses',
        'premio',
        'premio_total',
    ];
}
