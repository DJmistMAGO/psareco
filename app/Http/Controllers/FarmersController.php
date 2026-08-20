<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machinery;

class FarmersController extends Controller
{
    public function index() {

        $availableMachinery = Machinery::where('status', 'available')->get();

        return view('farmer.book-machinery', compact('availableMachinery'));
    }

    public function myBookings() {
        return view('farmer.my-bookings');
    }

    public function products() {
        return view('farmer.products');
    }
}
