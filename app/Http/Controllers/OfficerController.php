<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSlot;
use Carbon\Carbon;


class OfficerController extends Controller
{
    public function indexBooking(){

    $bookings = Booking::all();

    return view('admin.machinery-booking', compact('bookings'));

    }

    public function bookingCalendar(){

    return view('admin.booking-calendar');
    }

    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'Approved';
        $booking->save();

        return redirect()->back()->with('success', 'Booking approved successfully.');
    }


}
