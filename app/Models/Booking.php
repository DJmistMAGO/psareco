<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'user_id',
        'start_date',
        'start_day_type',
        'end_date',
        'end_day_type',
        'days',
        'total_hours',
        'total_amount',
        'status',
        'remarks'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days' => 'integer',
        'total_hours' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machinery::class, 'machine_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(BookingSlot::class)->orderBy('booking_date', 'asc');
    }

    public function recalculateTotalHours(): void
    {
        $this->update([
            'total_hours' => $this->slots()->sum('hours'),
        ]);
    }
}
