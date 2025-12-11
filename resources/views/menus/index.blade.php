@extends('layouts.app')

@section('content')
<style>
    /* RESET */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #F4F6F1;
        font-family: -apple-system, BlinkMacSystemFont, 
                    "SF Pro Text", "SF Pro Display", 
                    "Segoe UI", Roboto, sans-serif;
    }

    :root {
    --green-primary: #1c9943;
    --green-light: #E3F9EB;
    --shadow-soft: 
        0px 2px 6px rgba(0, 0, 0, 0.04),
        0px 8px 20px rgba(0, 0, 0, 0.06);
    --border-radius: 18px;
    --text-primary: #111827;
    --text-secondary: #6B7280;
}

    /* HEADER */
    .header {
        background: transparent;
        padding: 16px;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-weight: 1000; 
        color: #111827;
        font-size: 28px;
        letter-spacing: -0.025em;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-badge {
        background: transparent;
        color: #111827;
        padding: 6px 12px;
        border-radius: var(--border-radius);
        font-size: 14px;
        font-weight: 400;
        border: 1px solid black;
        display: flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .table-badge:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    /* SEARCH */
    .search-wrapper {
        position: relative;
        margin: 0;
        padding: 0;
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: black;
        font-size: 16px;
        z-index: 1;
    }

    .search-input {
        width: 100%;
        border: 1px solid white;
        border-radius: 50px;
        padding: 10px 15px 10px 48px;
        font-size: 15px;
        background: white;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(28, 153, 67, 0.1);
    }

    .search-input::placeholder {
        color: #9ca3af;
    }

    /* TABS */
    .tab-container {
        background: transparent;
        padding: 0px 16px 8px;
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .tab-container::-webkit-scrollbar {
        display: none;
    }

    .tab-nav {
        display: flex;
        gap: 24px;
        align-items: center;
    }

    .tab-item {
        padding: 8px 0 6px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 400;
        color: #626876ff;
        white-space: nowrap;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tab-item:hover {
        color: #374151;
    }

    .tab-active {
        color: var(--green-primary);
        border-bottom-color: var(--green-primary);
    }

    /* SECTION TITLE */
    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #111827;
        padding: 12px 16px 12px;
        background: #F4F6F1;
    }

    /* MENU GRID */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        padding: 0 16px 100px;
    }

    .menu-card {
        background: #f5f5f2;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .menu-card:active {
        transform: scale(0.98);
    }

    .menu-image {
        padding: 10px;
        width: 100%;
        height: 150px;
        border-radius: 25px;
        object-fit: cover;
    }

    .menu-body {
        padding: 0px 10px 10px 10px;
    }

    .menu-name {
        font-weight: 550;
        font-size: 14px;
        color: #111827;
        margin-bottom: 2px;
    }

    .menu-desc {
        font-size: 10px;
        color: #181a16;
        margin-bottom: 5px;
    }

    .menu-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .menu-price {
        font-size: 16px;
        font-weight: 500;
        color: black;
    }

    .add-btn {
        background: white;
        color: var(--green-primary);
        border: 1px solid var(--green-primary);
        width: 25px;
        height: 25px;
        border-radius: 8px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-btn:hover {
        background: var(--green-primary);
        color: white;
        transform: scale(1.05);
    }

    .add-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .add-btn.adding {
        background: var(--green-primary);
        color: white;
        animation: pulse 0.6s;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* STICKY CART */
    .sticky-cart {
        position: fixed;
        bottom: 20px;
        left: 16px;
        right: 16px;
        background: var(--green-primary);
        color: white;
        padding: 12px 20px;
        display: none;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(31, 171, 73, 0.3);
        align-items: center;
        justify-content: space-between;
        z-index: 1000;
        animation: slideUp 0.3s ease;
    }

    .sticky-cart.show {
        display: flex;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .cart-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 400;
    }

    .cart-icon {
        font-size: 15px;
    }

    .cart-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cart-total {
        font-size: 16px;
        font-weight: 400;
    }

    .cart-arrow {
        width: 24px;
        height: 24px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }

    .sticky-cart:active .cart-arrow {
        transform: scale(0.9);
    }

    /* Responsive */
    @media (min-width: 768px) {
        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 0 24px 120px;
        }
    }
</style>

<div class="header">
    <div class="header-top">
        <h1 class="logo">Choice.</h1>
        <div class="header-right">
            <div class="table-badge">
                Table {{ str_pad(session('table_number', '09'), 2, '0', STR_PAD_LEFT) }}
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
    <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-input" placeholder="Search Menu" id="searchInput" onkeyup="filterMenus()">
    </div>
</div>

<div class="tab-container">
    <div class="tab-nav">
        @foreach($categories as $category)
            @php $isPromo = $category === 'Promo'; @endphp
            <button class="tab-item {{ $activeCategory === $category ? 'tab-active' : '' }}" onclick="switchTab('{{ $category }}')">
                @if($isPromo)
                @endif
                {{ ucwords(str_replace('_', ' ', $category)) }}
            </button>
        @endforeach
    </div>
</div>

<div class="section-title">
    {{ ucwords(str_replace('_', ' ', $activeCategory ?? 'Main Menu')) }}
</div>

<div class="menu-grid" id="menuGrid">
    @forelse($menus as $menu)
        <div class="menu-card">
            <img src="{{ $menu['image'] }}" class="menu-image" alt="{{ $menu['name'] }}" onerror="this.src='https://via.placeholder.com/300x140?text={{ urlencode($menu['name']) }}'">
            <div class="menu-body">
                <div class="menu-name">{{ $menu['name'] }}</div>
                <div class="menu-desc">{{ $menu['description'] }}</div>
                <div class="menu-footer">
                    <div class="menu-price">Rp {{ number_format($menu['price'], 0, ',', '.') }}</div>
                    <button 
                        type="button" 
                        class="add-btn" 
                        onclick="addToCart(this, {{ $menu['id'] }}, '{{ addslashes($menu['name']) }}', {{ $menu['price'] }}, '{{ $menu['image'] }}')"
                    >
                        +
                    </button>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-muted p-4" style="grid-column: 1 / -1;">No menu in this category.</p>
    @endforelse
</div>

<div class="sticky-cart {{ $cartSummary['count'] > 0 ? 'show' : '' }}" id="stickyCart">
    <div class="cart-left">
        <i class="fas fa-shopping-cart cart-icon"></i>
        <span class="cart-text" id="cartText">{{ $cartSummary['count'] }} items added</span>
    </div>
    <a href="{{ route('checkout') }}" style="text-decoration: none; color: white; display: flex; align-items: center;">
        <div class="cart-right">
            <span class="cart-total" id="cartTotal">Rp {{ number_format($cartSummary['total'], 0, ',', '.') }}</span>
            <div class="cart-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </a>
</div>

<script>
    function switchTab(category) {
        window.location.href = `?category=${encodeURIComponent(category)}`;
    }

    function filterMenus() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.menu-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.querySelector('.menu-name').textContent.toLowerCase();
            const desc = card.querySelector('.menu-desc').textContent.toLowerCase();
            if (name.includes(input) || desc.includes(input)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0 && input) {
            document.getElementById('menuGrid').innerHTML = '<p class="text-center text-muted p-4" style="grid-column: 1 / -1;">No results found for "' + input + '".</p>';
        }
    }

    // ✅ AJAX Add to Cart dengan Toast Notification
    function addToCart(button, menuId, name, price, image) {
        // Prevent double click
        if (button.disabled) return;
        
        button.disabled = true;
        button.classList.add('adding');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';

        // AJAX Request
        fetch('{{ route("menu.addToCart") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                menu_id: menuId,
                name: name,
                price: price,
                image: image,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart display
                updateCartDisplay(data.cart_summary);
                
                // ✅ Show toast notification
                showToast(data.message || `${name} added to cart!`, 'success');
                
                // Reset button setelah 800ms
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('adding');
                    button.disabled = false;
                }, 800);
            } else {
                showToast('Failed to add item', 'error');
                resetButton(button, originalText);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
            resetButton(button, originalText);
        });
    }

    function resetButton(button, originalText) {
        button.innerHTML = originalText;
        button.classList.remove('adding');
        button.disabled = false;
    }

    // Update cart tanpa reload
    function updateCartDisplay(summary) {
        const stickyCart = document.getElementById('stickyCart');
        const cartText = document.getElementById('cartText');
        const cartTotal = document.getElementById('cartTotal');

        if (summary.count > 0) {
            stickyCart.classList.add('show');
            cartText.textContent = `${summary.count} items added`;
            cartTotal.textContent = `Rp ${formatNumber(summary.total)}`;
        } else {
            stickyCart.classList.remove('show');
        }
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>
@endsection