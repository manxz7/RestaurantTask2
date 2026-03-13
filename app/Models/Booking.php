<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // $fillable = senarai columns yang BOLEH diisi dari form
    // Ini adalah SECURITY feature — "Mass Assignment Protection"
    // Kalau tak letak $fillable, Laravel REJECT semua input dari form!
    protected $fillable = [
        'name',      // ← column dalam bookings table
        'email',
        'phone',
        'date',
        'time',
        'people',
        'message',
        'status'
    ];
}