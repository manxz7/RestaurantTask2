<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Import Models — supaya controller boleh guna
// Kalau tak import, PHP tak tahu Booking tu apa
use App\Models\Booking;
use App\Models\Contact;

class PageController extends Controller
{
    // ═══════════════════════════════════
    // FUNCTION 1: Papar home page
    // ═══════════════════════════════════
    public function home()
    {
        return view('pages.home');
    }

    // ═══════════════════════════════════
    // FUNCTION 2: Simpan booking
    // Dipanggil bila user submit booking form
    // ═══════════════════════════════════
    public function storeBooking(Request $request)
    {
        // VALIDATION — pastikan data yang user hantar betul
        // 'required' = wajib ada
        // 'email' = kena format email
        // 'date' = kena format tarikh
        // 'integer' = kena nombor bulat
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email',
            'phone'  => 'required|string',
            'date'   => 'required|date',
            'time'   => 'required',
            'people' => 'required|integer|min:1',
        ]);

        // SAVE KE DATABASE guna Booking Model
        // ::create() = INSERT INTO bookings (...) VALUES (...)
        Booking::create([
            'name'    => $request->name,
            // $request->name = data yang user taip dalam input name="name"
            'email'   => $request->email,
            'phone'   => $request->phone,
            'date'    => $request->date,
            'time'    => $request->time,
            'people'  => $request->people,
            'message' => $request->message,
            'status'  => 'pending'
            // status auto set 'pending' — user tak perlu isi ni
        ]);

        // REDIRECT balik ke home page dengan success message
        // with() = flash message — simpan message sekejap dalam session
return redirect('/#book-a-table')->with('booking_success', 'Booking berjaya! Kami akan hubungi anda.');    }

    // ═══════════════════════════════════
    // FUNCTION 3: Simpan contact message
    // ═══════════════════════════════════
    public function storeContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return redirect('/')->with('contact_success', 'Mesej berjaya dihantar!');
    }
}