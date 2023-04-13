<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'note',
        'total_quanty',
        'total_price',
        'status',
    ];
}
