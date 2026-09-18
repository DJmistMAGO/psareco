<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Machinery;
use Carbon\Carbon;

class OfficerController extends Controller
{
    public function indexBooking(Request $request)
    {
        $status = strtolower($request->get('status', 'pending'));
        $search = $request->get('search');

        $query = Booking::with(['user', 'machine']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('machine', function ($m) use ($search) {
                    $m->where('machinery_name', 'like', "%{$search}%")->orWhere('model', 'like', "%{$search}%");
                });
            });
        }

        $countsQuery = clone $query;
        $rawCounts = $countsQuery->selectRaw('LOWER(status) as status_name, count(*) as count')->groupBy('status_name')->pluck('count', 'status_name')->toArray();

        $statusCounts = [
            'pending' => $rawCounts['pending'] ?? 0,
            'approved' => $rawCounts['approved'] ?? 0,
            'completed' => $rawCounts['completed'] ?? 0,
            'declined' => ($rawCounts['declined'] ?? 0) + ($rawCounts['cancelled'] ?? 0),
        ];

        if (in_array($status, ['declined', 'cancelled'])) {
            $query->whereIn('status', ['Declined', 'Cancelled', 'declined', 'cancelled']);
        } else {
            $query->where('status', ucfirst($status));
        }

        $bookings = $query->latest()->paginate(5)->withQueryString();

        return view('admin.machinery-booking', compact('bookings', 'statusCounts'));
    }

    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'Approved';
        $booking->save();

        $machinery = Machinery::findOrFail($booking->machine_id);
        $machinery->status = 'Reserved';
        $machinery->save();

        return redirect()->back()->with('success', 'Booking approved successfully.');
    }

    public function declineBooking(Request $request, $id)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'max:1000'],
        ]);

        $booking = Booking::findOrFail($id);

        $booking->status = 'Declined';
        $booking->remarks = $request->remarks;
        $booking->save();

        return redirect()->back()->with('success', 'Booking declined successfully.');
    }
}
