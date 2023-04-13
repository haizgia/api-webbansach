<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'publisher_id',
        'name',
        'image',
        'price',
        'quanty',
        'author',
        'content',
    ];
}
