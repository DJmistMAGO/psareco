<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Machinery;
use App\Models\BookingSlot;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FarmersController extends Controller
{
    public function index()
    {
        $availableMachinery = Machinery::where('status', 'Available')->get();

        $disabledDatesByMachine = BookingSlot::whereHas('booking', function ($query) {
            $query->whereIn('status', ['Pending', 'Approved']);
        })
            ->whereDate('booking_date', '>=', Carbon::today())
            ->get(['machine_id', 'booking_date'])
            ->groupBy('machine_id')
            ->map(function ($slots) {
                return $slots
                    ->pluck('booking_date')
                    ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                    ->unique()
                    ->values()
                    ->toArray();
            })
            ->toArray();

        // dd($disabledDatesByMachine);

        //get all bookings for the authenticated user for pending and approved bookings
        $userBookings = Booking::where('user_id', Auth::id())->whereIn('status', ['Pending', 'Approved'])->get();

        return view('farmer.book-machinery', compact(
            'availableMachinery',
            'disabledDatesByMachine',
            'userBookings'
        ));
    }

    public function bookingDetails($id)
    {
        $booking = Booking::with('slots', 'machine')->findOrFail($id);

        // Ensure the authenticated user is the owner of the booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        //get the booking slots for the booking
        $bookingSlots = BookingSlot::where('booking_id', $id)->get();

        return view('farmer.booking-deatils', compact('booking', 'bookingSlots'));
    }

    public function myBookings()
    {
        return view('farmer.my-bookings');
    }

    public function products()
    {
        return view('farmer.products');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machineries,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'total_amount' => 'nullable|numeric|min:0',
        ]);


        return DB::transaction(function () use ($validated, $request) {
            $period = CarbonPeriod::create($validated['start_date'], $validated['end_date']);

            $booking = Booking::create([
                'machine_id'   => $validated['machine_id'],
                'user_id'      => Auth::id(),
                'start_date'   => $validated['start_date'],
                'end_date'     => $validated['end_date'],
                'days'         => $period->count(),
                'total_amount' => $validated['total_amount'] ?? 0,
                'status'       => 'Pending',
            ]);

            // $booking->machine->update(['status' => 'Reserved']);

            foreach ($period as $date) {
                $booking->slots()->create([
                    'booking_date' => $date->format('Y-m-d'),
                    'machine_id'   => $validated['machine_id'],
                    'start_time'   => null,
                    'end_time'     => null,
                    'hours'        => 0,
                ]);
            }

            return redirect()->route('farmers.index')->with('success', 'Machinery added successfully.');
        });
    }
}
