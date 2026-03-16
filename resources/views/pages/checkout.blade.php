@extends('layouts.app')

@section('content')
<section style="padding: 80px 0; min-height: 60vh;">
<div class="container">

    <h2 style="text-align:center; margin-bottom: 10px;">🧾 Checkout</h2>
    <p style="text-align:center; color:#999; margin-bottom: 40px;">Semak order dan isi details booking</p>

    <div class="row">

        {{-- Kiri: Form Booking Details --}}
        <div class="col-lg-7">
            <div style="background:white; border-radius:12px; padding:30px; box-shadow:0 2px 15px rgba(0,0,0,0.08); margin-bottom:20px;">
                <h5 style="margin-bottom:20px; border-bottom:2px solid #ce1212; padding-bottom:10px;">📋 Booking Details</h5>

                <form action="/checkout" method="POST" id="checkout-form">
                    @csrf

                    {{-- Hidden field untuk simpan cart data --}}
                    <input type="hidden" name="cart_data" id="cart_data">
                    <input type="hidden" name="total_price" id="total_price_input">

                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Nama Penuh *</label>
                            <input type="text" name="name" class="form-control" placeholder="contoh: Ahmad bin Ali" required>
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="contoh: ahmad@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">No. Telefon *</label>
                            <input type="text" name="phone" class="form-control" placeholder="contoh: 0123456789" required>
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Bilangan Orang *</label>
                            <input type="number" name="people" class="form-control" placeholder="contoh: 2" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Tarikh *</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Masa *</label>
                            <input type="time" name="time" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Nota Tambahan</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="contoh: Saya ada alahan makanan laut..."></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Mock Payment Section --}}
            <div style="background:white; border-radius:12px; padding:30px; box-shadow:0 2px 15px rgba(0,0,0,0.08);">
                <h5 style="margin-bottom:20px; border-bottom:2px solid #ce1212; padding-bottom:10px;">💳 Maklumat Pembayaran</h5>

                <div style="background:#f8f9fa; border-radius:8px; padding:20px; margin-bottom:15px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">
                        <span style="font-size:24px;">💳</span>
                        <strong>Kad Kredit / Debit</strong>
                    </div>
                    <div class="row gy-3">
                        <div class="col-12">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Nombor Kad</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19"
                                oninput="formatCard(this)">
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">Tarikh Luput</label>
                            <input type="text" class="form-control" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600; margin-bottom:5px; display:block;">CVV</label>
                            <input type="text" class="form-control" placeholder="123" maxlength="3">
                        </div>
                    </div>
                </div>

                <p style="color:#999; font-size:12px; text-align:center;">
                    🔒 Ini adalah mock payment untuk demo. Tiada duit sebenar dikenakan.
                </p>
            </div>
        </div>

        {{-- Kanan: Order Summary --}}
        <div class="col-lg-5">
            <div style="background:white; border-radius:12px; padding:30px; box-shadow:0 2px 15px rgba(0,0,0,0.08); position:sticky; top:100px;">
                <h5 style="margin-bottom:20px; border-bottom:2px solid #ce1212; padding-bottom:10px;">🛒 Order Summary</h5>

                <div id="checkout-items" style="margin-bottom:15px;">
                    {{-- Diisi oleh JavaScript --}}
                </div>

                <div style="border-top:2px solid #eee; padding-top:15px; margin-bottom:20px;">
                    <div style="display:flex; justify-content:space-between; font-size:14px; color:#666; margin-bottom:8px;">
                        <span>Subtotal</span>
                        <span>RM <span id="subtotal">0.00</span></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:14px; color:#666; margin-bottom:8px;">
                        <span>Service Charge (0%)</span>
                        <span>RM 0.00</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:bold; margin-top:10px;">
                        <span>Total</span>
                        <span style="color:#ce1212;">RM <span id="checkout-total">0.00</span></span>
                    </div>
                </div>

                {{-- Confirm Pay Button --}}
                <button onclick="submitOrder()" style="
                    display:block;
                    width:100%;
                    background:#ce1212;
                    color:white;
                    border:none;
                    padding:16px;
                    border-radius:8px;
                    font-size:16px;
                    font-weight:bold;
                    cursor:pointer;
                    transition: background 0.3s;
                " onmouseover="this.style.background='#a50e0e'" onmouseout="this.style.background='#ce1212'">
                    ✅ Confirm & Pay
                </button>

                <a href="/cart" style="display:block; text-align:center; margin-top:15px; color:#999; font-size:14px;">
                    ← Balik ke Cart
                </a>

                {{-- Cart kosong warning --}}
                <div id="empty-warning" style="display:none; background:#fff3cd; border-radius:8px; padding:15px; margin-top:15px; text-align:center; color:#856404;">
                    ⚠️ Cart kosong! Sila tambah makanan dulu.
                </div>
            </div>
        </div>

    </div>
</div>
</section>

<script>
// Load cart dan papar dalam checkout
document.addEventListener('DOMContentLoaded', function() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length === 0) {
        document.getElementById('empty-warning').style.display = 'block';
        document.getElementById('checkout-items').innerHTML = '<p style="color:#999; text-align:center;">Tiada item dalam cart.</p>';
        return;
    }

    let itemsHTML = '';
    let total = 0;

    cart.forEach(item => {
        const subtotal = item.price * item.qty;
        total += subtotal;

        itemsHTML += `
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid #f0f0f0;">
            <img src="/img/menu/${item.image}" style="width:50px; height:50px; object-fit:cover; border-radius:6px;"
                 onerror="this.src='/img/menu/menu-item-1.png'">
            <div style="flex:1;">
                <div style="font-weight:600; font-size:14px;">${item.name}</div>
                <div style="color:#999; font-size:13px;">x${item.qty}</div>
            </div>
            <div style="font-weight:bold; color:#ce1212;">RM ${subtotal.toFixed(2)}</div>
        </div>`;
    });

    document.getElementById('checkout-items').innerHTML = itemsHTML;
    document.getElementById('subtotal').textContent = total.toFixed(2);
    document.getElementById('checkout-total').textContent = total.toFixed(2);

    // Simpan cart data dalam hidden input untuk dihantar ke server
    document.getElementById('cart_data').value = JSON.stringify(cart);
    document.getElementById('total_price_input').value = total.toFixed(2);
});

// Format nombor kad — tambah space setiap 4 digit
function formatCard(input) {
    let value = input.value.replace(/\D/g, ''); // buang semua bukan nombor
    value = value.replace(/(.{4})/g, '$1 ').trim(); // tambah space setiap 4
    input.value = value;
}

// Submit order
function submitOrder() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length === 0) {
        alert('Cart kosong! Sila tambah makanan dulu.');
        return;
    }

    // Submit form
    document.getElementById('checkout-form').submit();
}
</script>
@endsection