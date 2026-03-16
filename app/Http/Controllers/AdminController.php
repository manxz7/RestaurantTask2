<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Menu; // ← tambah import Menu model

class AdminController extends Controller
{
    // ═══════════════════════════
    // BOOKINGS (dah ada)
    // ═══════════════════════════
    public function bookings()
    {
        $bookings = Booking::latest()->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status updated!');
    }

    public function deleteBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Booking deleted!');
    }

    // ═══════════════════════════
    // MENUS (BARU)
    // ═══════════════════════════

    // Papar senarai semua menu
    public function menus()
    {
        // Ambil semua menu dari database, susun dari terbaru
        $menus = Menu::latest()->get();

        // Hantar ke view admin/menus.blade.php
        return view('admin.menus', compact('menus'));
    }

    // Simpan menu baru
    public function storeMenu(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'price'    => 'required|numeric|min:0',
        'category' => 'required|in:starters,breakfast,lunch,dinner',
        'image'    => 'nullable|image|max:2048',
        // nullable = tak wajib upload gambar
        // image = kena file gambar (jpg,png,gif,svg,webp)
        // max:2048 = maksimum 2MB
    ]);

    // Handle upload gambar
    $imageName = null; // default null kalau takde gambar

    if ($request->hasFile('image')) {
        // Ambil file gambar dari request
        $file = $request->file('image');

        // Generate nama file unik — guna timestamp
        // Contoh: 1741234567_nasi-lemak.jpg
        $imageName = time() . '_' . $file->getClientOriginalName();

        // Pindahkan gambar ke folder public/img/menu/
        $file->move(public_path('img/menu'), $imageName);
    }

    Menu::create([
        'name'         => $request->name,
        'ingredients'  => $request->ingredients,
        'price'        => $request->price,
        'category'     => $request->category,
        'image'        => $imageName, // simpan nama file dalam database
        'is_available' => true,
    ]);

    return redirect('/admin/menus')->with('success', 'Menu berjaya ditambah!');
}

    // Delete menu
    public function deleteMenu($id)
    {
        Menu::findOrFail($id)->delete();
        return redirect('/admin/menus')->with('success', 'Menu berjaya dipadam!');
    }

    // Papar form edit
    public function editMenu($id)
    {
        // Cari menu dengan ID tu
        // findOrFail = kalau tak jumpa, auto return 404
        $menu = Menu::findOrFail($id);
        return view('admin.edit-menu', compact('menu'));
    }

    // Update menu
    public function updateMenu(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'category' => 'required|in:starters,breakfast,lunch,dinner',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'name'        => $request->name,
            'ingredients' => $request->ingredients,
            'price'       => $request->price,
            'category'    => $request->category,
        ]);

        return redirect('/admin/menus')->with('success', 'Menu berjaya dikemaskini!');
    }
}