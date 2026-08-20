<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Machinery extends Model
{
    use HasFactory;

    protected $fillable = [
        'machinery_name',
        'model',
        'serial_number',
        'price',
        'image_path',
        'status'
    ];

    protected $casts = [
        'total_unit' => 'integer',
        'price' => 'decimal:2',
    ];

    
}
