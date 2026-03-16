@extends('layouts.app')

@section('content')
<section class="cart-section" style="padding: 80px 0; min-height: 60vh;">
<div class="container">

    <h2 style="text-align:center; margin-bottom: 40px;">🛒 Your Cart</h2>

    {{-- Cart kosong --}}
    <div id="cart-empty" style="display:none; text-align:center; padding: 60px;">
        <p style="font-size: 18px; color: #999;">Cart kosong! Pergi pilih makanan dulu.</p>
        <a href="/#menu" class="btn" style="background:#ce1212; color:white; padding:10px 30px; border-radius:6px; text-decoration:none;">
            Lihat Menu
        </a>
    </div>

    {{-- Cart ada items --}}
    <div id="cart-content">
        <div class="row">

            {{-- Senarai Items --}}
            <div class="col-lg-7">
                <div id="cart-items"></div>
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-5">
                <div style="background:#f8f8f8; border-radius:12px; padding:30px; position:sticky; top:100px;">
                    <h4 style="margin-bottom:20px;">📋 Order Summary</h4>

                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 15px;">
                        <div id="summary-items"></div>
                    </div>

                    <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:bold; margin-bottom:25px;">
                        <span>Total:</span>
                        <span style="color:#ce1212;">RM <span id="cart-total">0.00</span></span>
                    </div>

                    <a href="/checkout" class="btn" id="checkout-btn" style="
                        display:block;
                        background:#ce1212;
                        color:white;
                        text-align:center;
                        padding:14px;
                        border-radius:8px;
                        text-decoration:none;
                        font-size:16px;
                        font-weight:bold;
                    ">
                        Proceed to Checkout →
                    </a>

                    <a href="/#menu" style="display:block; text-align:center; margin-top:15px; color:#999; font-size:14px;">
                        ← Tambah lagi makanan
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
</section>

<style>
.cart-item-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.cart-item-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 20px;
}
.cart-item-info { flex: 1; }
.cart-item-name { font-weight: bold; font-size: 16px; margin-bottom: 4px; }
.cart-item-price { color: #ce1212; font-weight: bold; }
.qty-btn {
    background: #f0f0f0;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
}
.qty-btn:hover { background: #ddd; }
.qty-display {
    display: inline-block;
    width: 35px;
    text-align: center;
    font-weight: bold;
}
.remove-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 20px;
    margin-left: 15px;
}
</style>

<script>
// Bila page load, render cart
document.addEventListener('DOMContentLoaded', renderCart);

function renderCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const cartContent = document.getElementById('cart-content');
    const cartEmpty   = document.getElementById('cart-empty');
    const cartItems   = document.getElementById('cart-items');
    const summaryItems = document.getElementById('summary-items');
    const cartTotal   = document.getElementById('cart-total');

    // Kalau cart kosong
    if (cart.length === 0) {
        cartContent.style.display = 'none';
        cartEmpty.style.display = 'block';
        return;
    }

    cartContent.style.display = 'block';
    cartEmpty.style.display = 'none';

    // Render setiap item
    let itemsHTML = '';
    let summaryHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        const subtotal = item.price * item.qty;
        total += subtotal;

        // Card untuk setiap item
        itemsHTML += `
        <div class="cart-item-card">
            <img src="/img/menu/${item.image}" class="cart-item-img" alt="${item.name}"
                 onerror="this.src='/img/menu/menu-item-1.png'">
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">RM ${item.price.toFixed(2)} seunit</div>
                <div style="margin-top:10px;">
                    <button class="qty-btn" onclick="updateQty(${index}, -1)">−</button>
                    <span class="qty-display">${item.qty}</span>
                    <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                </div>
            </div>
            <div>
                <div style="font-weight:bold; color:#333;">RM ${subtotal.toFixed(2)}</div>
                <button class="remove-btn" onclick="removeItem(${index})">🗑️</button>
            </div>
        </div>`;

        // Summary di sebelah kanan
        summaryHTML += `
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px;">
            <span>${item.name} x${item.qty}</span>
            <span>RM ${subtotal.toFixed(2)}</span>
        </div>`;
    });

    cartItems.innerHTML = itemsHTML;
    summaryItems.innerHTML = summaryHTML;
    cartTotal.textContent = total.toFixed(2);
}

// Tambah atau kurang quantity
function updateQty(index, change) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart[index].qty += change;

    // Kalau qty jadi 0, remove item
    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

// Remove item dari cart
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1); // splice = buang 1 item dari index tu
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

// Update cart count kat navbar
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const total = cart.reduce((sum, item) => sum + item.qty, 0);
    const countEl = document.getElementById('cart-count');
    if (countEl) countEl.textContent = total;
}
</script>
@endsection