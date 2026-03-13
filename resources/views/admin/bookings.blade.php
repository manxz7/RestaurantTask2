<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Bookings</title>
    {{-- Guna Bootstrap CDN untuk styling --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .badge-pending    { background: #ffc107; color: #000; }
        .badge-confirmed  { background: #198754; color: #fff; }
        .badge-cancelled  { background: #dc3545; color: #fff; }
    </style>
</head>
<body>

<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🍽️ Bookings Admin Panel</h2>
        <a href="/" class="btn btn-outline-secondary">← Back to Website</a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3>{{ $bookings->where('status', 'pending')->count() }}</h3>
                    <p class="mb-0 text-warning">⏳ Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3>{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                    <p class="mb-0 text-success">✅ Confirmed</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h3>{{ $bookings->where('status', 'cancelled')->count() }}</h3>
                    <p class="mb-0 text-danger">❌ Cancelled</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>All Bookings ({{ $bookings->count() }} total)</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>People</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through semua bookings --}}
                    @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->name }}</td>
                        <td>{{ $booking->email }}</td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ $booking->date }}</td>
                        <td>{{ $booking->time }}</td>
                        <td>{{ $booking->people }}</td>

                        {{-- Status Badge --}}
                        <td>
                            <span class="badge badge-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            {{-- Update Status Form --}}
                            <form action="/admin/bookings/{{ $booking->id }}/status"
                                  method="POST" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm d-inline w-auto"
                                        onchange="this.form.submit()">
                                    <option value="pending"   {{ $booking->status == 'pending'   ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>

                            {{-- Delete Form --}}
                            <form action="/admin/bookings/{{ $booking->id }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this booking?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    {{-- Kalau takde bookings langsung --}}
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No bookings yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
