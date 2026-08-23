<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{

    public function index()
{
    return view('admin.booking-calendar');
}
    public function calendarSchedule(Request $request)
    {
        $bookings = Booking::where('status', 'Approved')
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->user->name  . ' - ' . $booking->machine->machinery_name,
                'start' => $booking->start_date,
                'end' => Carbon::parse($booking->end_date)
                    ->addDay()
                    ->format('Y-m-d'),
                'allDay' => true,
                'color' => match ($booking->machine_id) {
                    1 => '#2c7a56',
                    2 => '#2563eb',
                    3 => '#d97706',
                    4 => '#7c3aed',
                    5 => '#dc2626',
                    default => '#64748b',
                },
            ];
        });

        return response()->json($events);
    }
}
