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
        $bookings = Booking::where('status', 'Approved')->get();

        $events = $bookings->map(function ($booking) {
            $start = Carbon::parse($booking->start_date);
            $end = Carbon::parse($booking->end_date);

            // Inclusive day count (e.g. same day = 1 day, Mon–Wed = 3 days)
            $totalDays = $start->diffInDays($end) + 1;

            return [
                'id' => $booking->id,
                'title' => $booking->user->name . ' - ' . $booking->machine->machinery_name,
                'start' => $booking->start_date,
                'end' => $end->copy()->addDay()->format('Y-m-d'),
                'allDay' => true,
                'color' => match ($booking->machine_id) {
                    1 => '#2c7a56',
                    2 => '#2563eb',
                    3 => '#d97706',
                    4 => '#7c3aed',
                    5 => '#dc2626',
                    default => '#64748b',
                },
                'extendedProps' => [
                    'renterName' => $booking->user->name,
                    'machineName' => $booking->machine->machinery_name,
                    'startDate' => $start->format('M d, Y'),
                    'endDate' => $end->format('M d, Y'),
                    'totalDays' => $totalDays,
                    'status' => $booking->status,
                ],
            ];
        });

        return response()->json($events);
    }
}
