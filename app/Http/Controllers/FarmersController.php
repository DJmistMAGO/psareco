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
        $availableMachinery = Machinery::all();

        // Query active bookings from today onwards
        $bookings = Booking::whereIn('status', ['Pending', 'Approved'])
            ->whereDate('end_date', '>=', Carbon::today())
            ->get(['machine_id', 'start_date', 'end_date', 'start_day_type', 'end_day_type']);

        $bookingDetailsByMachine = [];

        foreach ($bookings as $booking) {
            $startDate = Carbon::parse($booking->start_date)->startOfDay();
            $endDate = Carbon::parse($booking->end_date)->startOfDay();

            // Convert CarbonPeriod to a standard zero-indexed array
            $period = CarbonPeriod::create($startDate, $endDate)->toArray();
            $totalDays = count($period);
            $machineId = $booking->machine_id;

            foreach ($period as $i => $date) {
                $formattedDate = $date->format('Y-m-d');

                // Determine session type using zero-indexed integer ($i)
                if ($totalDays === 1) {
                    // Single-day booking
                    $dayType = $booking->start_day_type;
                } elseif ($i === 0) {
                    // Start date of multi-day booking
                    $dayType = $booking->start_day_type;
                } elseif ($i === $totalDays - 1) {
                    // End date of multi-day booking
                    $dayType = $booking->end_day_type;
                } else {
                    // Middle dates of multi-day booking
                    $dayType = 'Whole Day';
                }

                // Initialize machine date array if not set
                if (!isset($bookingDetailsByMachine[$machineId][$formattedDate])) {
                    $bookingDetailsByMachine[$machineId][$formattedDate] = [];
                }

                // Prevent duplicate session entries
                if (!in_array($dayType, $bookingDetailsByMachine[$machineId][$formattedDate])) {
                    $bookingDetailsByMachine[$machineId][$formattedDate][] = $dayType;
                }
            }
        }

        //dd($bookingDetailsByMachine);

        $userBookings = Booking::where('user_id', Auth::id())
            ->whereIn('status', ['Pending', 'Approved'])
            ->get();

        return view('farmer.book-machinery', compact(
            'availableMachinery',
            'bookingDetailsByMachine',
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

        $machinery = Machinery::findOrFail($booking->machine_id);
        $machinery->status = 'Available';
        $machinery->save();

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
                'description'     => $item->description,
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

        $request->merge([
            'end_day_type' => $request->input('end_day_type', $request->input('start_day_type'))
        ]);

        $validated = $request->validate([
            'machine_id' => 'required|exists:machineries,id',
            'start_date'     => 'required|date|after_or_equal:today',
            'start_day_type' => 'required|in:Whole Day,Morning Half Day,Afternoon Half Day',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'end_day_type'   => 'required|in:Whole Day,Morning Half Day,Afternoon Half Day',
            'total_amount' => 'nullable|numeric|min:0',
        ]);


        return DB::transaction(function () use ($validated, $request) {
            $period = CarbonPeriod::create($validated['start_date'], $validated['end_date']);

            $booking = Booking::create([
                'machine_id'   => $validated['machine_id'],
                'user_id'      => Auth::id(),
                'start_date'     => $validated['start_date'],
                'start_day_type' => $validated['start_day_type'],
                'end_date'       => $validated['end_date'],
                'end_day_type'   => $validated['end_day_type'],
                'days'         => $period->count(),
                'total_amount' => $validated['total_amount'] ?? 0,
                'status'       => 'Pending',
            ]);

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
