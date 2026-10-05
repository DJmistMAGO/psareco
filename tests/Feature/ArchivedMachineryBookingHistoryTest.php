<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Machinery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchivedMachineryBookingHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_archived_machinery_remains_available_to_booking_history_and_calendar(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $this->actingAs($user);

        $machinery = Machinery::create([
            'machinery_name' => 'Archived Tractor',
            'model' => 'Test Model',
            'serial_number' => 'ARCHIVED-TRACTOR-001',
            'price' => '100.00',
            'image_path' => 'test.png',
        ]);

        $booking = Booking::create([
            'machine_id' => $machinery->id,
            'user_id' => $user->id,
            'start_date' => today()->toDateString(),
            'end_date' => today()->toDateString(),
            'status' => 'Approved',
        ]);

        $slot = BookingSlot::create([
            'booking_id' => $booking->id,
            'machine_id' => $machinery->id,
            'booking_date' => today()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
        ]);

        $machinery->delete();

        $this->assertSame(0, Machinery::count());
        $this->assertSame('Archived Tractor', $booking->fresh()->machine->machinery_name);
        $this->assertSame('Archived Tractor', $slot->fresh()->machine->machinery_name);

        $this->getJson(route('schedule.booking-calendar'))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.title', $user->name . ' - Archived Tractor')
            ->assertJsonPath('0.extendedProps.machineName', 'Archived Tractor');
    }
}
