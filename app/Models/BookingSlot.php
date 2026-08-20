<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'booking_date',
        'start_time',
        'end_time',
        'hours',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'hours' => 'decimal:2',
    ];

    /* --- Boot & Events --- */

    protected static function booted(): void
    {
        // Automatically calculate hours when start_time and end_time are set or updated
        static::saving(function (BookingSlot $slot) {
            if ($slot->start_time && $slot->end_time) {
                $start = Carbon::parse($slot->start_time);
                $end = Carbon::parse($slot->end_time);

                // Calculate duration in hours
                $slot->hours = round($start->diffInMinutes($end) / 60, 2);
            } else {
                $slot->hours = 0;
            }
        });

        // Sync parent booking total_hours when a slot is saved or deleted
        static::saved(function (BookingSlot $slot) {
            $slot->booking?->recalculateTotalHours();
        });

        static::deleted(function (BookingSlot $slot) {
            $slot->booking?->recalculateTotalHours();
        });
    }

    /* --- Relationships --- */

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
