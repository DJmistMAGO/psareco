<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machinery extends Model
{
    use HasFactory, SoftDeletes;

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
