<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billdetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'bill_id',
        'book_id',
        'price',
        'quanty',
        'total',
    ];
}
