<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'type',
        'quantity',
        'unit',
        'description',
        'price',
        'reorder_level',
        'expiration_date',
        'image_path',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'expiration_date' => 'date',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class);
    }

    public function ensureInitialBatch(): void
    {
        if (!$this->batches()->exists() && $this->quantity > 0) {
            $this->batches()->create([
                'quantity' => $this->quantity,
                'expiration_date' => $this->expiration_date,
            ]);
        }
    }

    public function syncBatchSummary(): void
    {
        $this->forceFill(['quantity' => $this->batches()->sum('quantity')]);
        $this->expiration_date = $this->batches()
            ->where('quantity', '>', 0)
            ->whereNotNull('expiration_date')
            ->orderBy('expiration_date')
            ->value('expiration_date');
        $this->save();
    }
}
