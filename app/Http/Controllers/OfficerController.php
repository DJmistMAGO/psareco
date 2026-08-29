<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSlot;
use Carbon\Carbon;


class OfficerController extends Controller
{
    public function indexBooking(Request $request)
{
    // 1. Get status and search parameters from the URL
    $status = strtolower($request->get('status', 'pending'));
    $search = $request->get('search');

    // 2. Base query with relationships loaded
    $query = Booking::with(['user', 'machine']);

    // 3. Filter by search query if present
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('machine', function ($m) use ($search) {
                $m->where('machinery_name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        });
    }

    // 4. Calculate total counts for each tab (ignoring status filter, keeping search filter if applied)
    $countsQuery = clone $query;
    $rawCounts = $countsQuery->selectRaw('LOWER(status) as status_name, count(*) as count')
        ->groupBy('status_name')
        ->pluck('count', 'status_name')
        ->toArray();

    $statusCounts = [
        'pending'   => $rawCounts['pending'] ?? 0,
        'approved'  => $rawCounts['approved'] ?? 0,
        'completed' => $rawCounts['completed'] ?? 0,
        'declined'  => ($rawCounts['declined'] ?? 0) + ($rawCounts['cancelled'] ?? 0),
    ];

    // 5. Apply status filter to the main table query
    if (in_array($status, ['declined', 'cancelled'])) {
        $query->whereIn('status', ['Declined', 'Cancelled', 'declined', 'cancelled']);
    } else {
        $query->where('status', ucfirst($status));
    }

    // 6. Paginate and keep query string parameters
    $bookings = $query->latest()->paginate(5)->withQueryString();

    return view('admin.machinery-booking', compact('bookings', 'statusCounts'));
}

    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'Approved';
        $booking->save();

        return redirect()->back()->with('success', 'Booking approved successfully.');
    }

    public function declineBooking($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'Declined';
        $booking->save();

        return redirect()->back()->with('success', 'Booking approved successfully.');
    }


}
