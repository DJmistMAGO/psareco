<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Machinery;
use App\Models\BookingSlot;
use App\Models\Inventory;
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
    $user = auth()->user();

    if ($user->hasRole('farmer') && $booking->user_id !== $user->id) {
        abort(403, 'Unauthorized action.');
    }

    $bookingSlots = BookingSlot::where('booking_id', $id)->get();

    return view('farmer.booking-deatils', compact('booking', 'bookingSlots'));
}


    public function updateBookingSlot(Request $request, $slotId)
    {
        // dd($request->all());

        $request->validate([
            'slot_id' => 'required|array',
            'slot_id.*' => 'required|exists:booking_slots,id',

            'start_time' => 'required|array',
            'start_time.*' => 'nullable|date_format:H:i',

            'end_time' => 'required|array',
            'end_time.*' => 'nullable|date_format:H:i',
        ]);

        // dd($request->all());

        foreach ($request->slot_id as $index => $slotId) {

            $startTime = $request->start_time[$index] ?? null;
            $endTime = $request->end_time[$index] ?? null;

            // Skip empty rows
            if (empty($startTime) && empty($endTime)) {
                continue;
            }

            // Don't allow only one time
            if (empty($startTime) || empty($endTime)) {
                return back()->withErrors([
                    'time' => 'Please provide both start time and end time.'
                ]);
            }

            $start = Carbon::createFromFormat('H:i', $startTime);
            $end = Carbon::createFromFormat('H:i', $endTime);

            if ($end->lessThan($start)) {
                $end->addDay();
            }

            $hours = $start->diffInMinutes($end) / 60;

            BookingSlot::where('id', $slotId)->update([
                'start_time' => $startTime,
                'end_time' => $endTime,
                'hours' => $hours,
            ]);
        }

        return back()->with('success', 'Booking slots updated successfully.');
    }

    public function completeBooking(Request $request, $bookingId)
    {

        // dd($request);

        $booking = Booking::findOrFail($bookingId);

        $validateData = $request->validate([
            'total_hours' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
        ]);

        $booking->update([
            'total_hours' => $validateData['total_hours'],
            'total_amount' => $validateData['total_cost'],
            'status' => 'Completed',
        ]);

        return redirect()->route('farmers.myBookings')->with('success', 'Booking completed successfully.');
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())->whereIn('status', ['Completed', 'Declined'])->get();


        return view('farmer.my-bookings', compact('bookings'));
    }

    public function products(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('availability') && $request->availability !== 'all') {
            if ($request->availability === 'in_stock') {
                $query->where('quantity', '>', 0);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where('quantity', '<=', 0);
            }
        }

        $products = $query->latest()->get()->map(function ($item) {
            return [
                'id'              => $item->id,
                'name'            => $item->name,
                'type'            => $item->type,
                'price'           => $item->price,
                'unit'            => $item->unit,
                'totalUnits'      => $item->quantity,
                'reorder_level'   => $item->reorder_level,
                'expiration_date' => $item->expiration_date,
                'image'           => $item->image_path ? asset('storage/' . $item->image_path) : null,
            ];
        });


        return view('farmer.products', compact('products'));
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

    public function deleteBooking(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('farmers.index')->with('success', 'Booking deleted successfully.');
    }
}
