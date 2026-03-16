<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin — Menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🍽️ Menu Admin Panel</h2>
        <div>
            <a href="/admin/bookings" class="btn btn-outline-secondary me-2">📋 Bookings</a>
            <a href="/" class="btn btn-outline-dark">← Website</a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <div class="row">

        {{-- Form Tambah Menu --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <strong>➕ Tambah Menu Baru</strong>
                </div>
                <div class="card-body">
                    <form action="/admin/menus" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Makanan</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="contoh: Nasi Lemak" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bahan-bahan</label>
                            <input type="text" name="ingredients" class="form-control"
                                   placeholder="contoh: nasi, santan, ikan bilis">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar Makanan</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Saiz maksimum: 2MB</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga (RM)</label>
                            <input type="number" name="price" class="form-control"
                                   step="0.01" placeholder="contoh: 12.50" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="starters">Starters</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            💾 Simpan Menu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Senarai Menu --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <strong>📋 Senarai Menu ({{ $menus->count() }} item)</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $menu)
                            <tr>
                                <td>{{ $menu->id }}</td>
                                <td>
                                    {{-- Tunjuk gambar kalau ada --}}
                                    @if($menu->image)
                                        <img src="{{ asset('img/menu/' . $menu->image) }}"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:5px;"
                                            class="me-2">
                                    @else
                                        <span class="me-2">🍽️</span>
                                    @endif

                                    <strong>{{ $menu->name }}</strong>
                                    @if($menu->ingredients)
                                        <br><small class="text-muted">{{ $menu->ingredients }}</small>
                                    @endif
                                </td>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($menu->category) }}
                                    </span>
                                </td>
                                <td>RM {{ number_format($menu->price, 2) }}</td>
                                <td>
                                    {{-- Edit Button --}}
                                    <a href="/admin/menus/{{ $menu->id }}/edit"
                                       class="btn btn-warning btn-sm">✏️</a>

                                    {{-- Delete Button --}}
                                    <form action="/admin/menus/{{ $menu->id }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete {{ $menu->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada menu lagi. Tambah menu di sebelah kiri!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
