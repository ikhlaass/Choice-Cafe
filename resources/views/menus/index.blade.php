@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endpush

@section('content')
<div class="menu-app-frame">
    <!-- Minimalist Header -->
    <header class="app-header">
        <div class="header-brand">
            <h1 class="brand-title">Choice Cafe</h1>
            <span class="brand-sub">Self Order</span>
        </div>
        <div class="table-badge">
            <span class="table-dot"></span>
            <span>Meja {{ str_pad(session('table_number', '01'), 2, '0', STR_PAD_LEFT) }}</span>
        </div>
    </header>

    <!-- Quiet Search Bar -->
    <div class="search-wrap">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input 
                type="text" 
                class="search-input" 
                placeholder="Cari makanan atau minuman..." 
                id="searchInput" 
                onkeyup="filterMenus()"
            >
        </div>
    </div>

    <!-- Minimal Category Chips -->
    <div class="category-tabs-wrap">
        <div class="category-tabs">
            @php
                $categoryLabels = [
                    'All' => 'Semua',
                    'Main Menu' => 'Makanan',
                    'Appetizer' => 'Camilan',
                    'Drinks' => 'Minuman',
                    'Desserts' => 'Penutup',
                ];
            @endphp
            @foreach($categories as $cat)
                <button 
                    type="button" 
                    class="cat-chip {{ $activeCategory === $cat ? 'active' : '' }}" 
                    onclick="switchTab('{{ $cat }}')"
                >
                    {{ $categoryLabels[$cat] ?? $cat }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Section Heading -->
    <div class="menu-heading-row">
        <h2 class="menu-heading-title">
            {{ $activeCategory === 'All' ? 'Daftar Menu' : ($categoryLabels[$activeCategory] ?? $activeCategory) }}
        </h2>
        <span class="menu-count-pill" id="menuCount">{{ count($menus) }} Menu</span>
    </div>

    <!-- Clean 2-Column Menu Grid -->
    <div class="menu-grid" id="menuListWrap">
        @forelse($menus as $menu)
            <div class="food-item-row" data-category="{{ $menu['category'] }}">
                <div class="food-media">
                    <img 
                        src="{{ $menu['image'] }}" 
                        alt="{{ $menu['name'] }}" 
                        class="food-img"
                        loading="lazy"
                        onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&auto=format&fit=crop&q=80'"
                    >
                </div>
                <div class="food-content">
                    <h3 class="food-name-title">{{ $menu['name'] }}</h3>
                    <p class="food-desc-text">{{ $menu['description'] }}</p>
                    <div class="food-footer">
                        <span class="food-price-tag">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                        <button 
                            type="button" 
                            class="btn-add-item" 
                            aria-label="Tambah {{ $menu['name'] }}"
                            title="Tambah ke pesanan"
                            onclick="addToCart(this, {{ $menu['id'] }}, '{{ addslashes($menu['name']) }}', {{ $menu['price'] }}, '{{ $menu['image'] }}')"
                        >
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-menu-box">
                <i class="fas fa-utensils empty-icon"></i>
                <p class="empty-title">Belum ada menu di kategori ini</p>
            </div>
        @endforelse
    </div>

    <!-- Minimalist Floating Cart Pill -->
    <div class="floating-cart-wrapper {{ ($cartSummary['count'] ?? 0) > 0 ? 'show' : '' }}" id="stickyCart">
        <a href="{{ route('checkout') }}" class="floating-cart-pill">
            <div class="cart-pill-left">
                <div class="cart-pill-icon">
                    <i class="fas fa-bag-shopping"></i>
                </div>
                <div class="cart-pill-info">
                    <span class="cart-pill-count" id="cartText">{{ $cartSummary['count'] ?? 0 }} Menu</span>
                    <span class="cart-pill-total" id="cartTotal">Rp {{ number_format($cartSummary['total'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="cart-pill-right">
                <span>Pesanan</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.addToCartUrl = '{{ route("menu.addToCart") }}';
    </script>
    <script src="{{ asset('js/menu.js') }}"></script>
@endpush