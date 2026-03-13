<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Contact;

class AdminController extends Controller
{
    // Papar semua bookings
    public function bookings()
    {
        // Ambil semua bookings dari database
        // latest() = susun dari yang terbaru dulu
        $bookings = Booking::latest()->get();

        // Hantar data $bookings ke view
        return view('admin.bookings', compact('bookings'));
    }

    // Update status booking
    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status updated!');
    }

    // Delete booking
    public function deleteBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Booking deleted!');
    }
}