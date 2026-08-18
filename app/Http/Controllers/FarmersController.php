<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FarmersController extends Controller
{
    public function index() {
        return view('farmer.book-machinery');
    }

    public function myBookings() {
        return view('farmer.my-bookings');
    }

    public function products() {
        return view('farmer.products');
    }
}
