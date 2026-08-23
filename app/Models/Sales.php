<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'total',
        'buyer_name',
        'sale_date',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'price'     => 'decimal:2',
        'total'     => 'decimal:2',
        'quantity'  => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Inventory::class);
    }
}
