<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'type',
        'quantity',
        'unit',
        'price',
        'reorder_level',
        'expiration_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'expiration_date' => 'date',
    ];
}
