@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endpush

@section('content')
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
                    <div class="menu-price">Rp{{ number_format($menu['price'], 0, ',', '.') }}</div>
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
            <span class="cart-total" id="cartTotal">Rp{{ number_format($cartSummary['total'], 0, ',', '.') }}</span>
            <div class="cart-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </a>
</div>
@endsection

@push('scripts')
    <script>
        // Set URL for AJAX calls (Laravel route)
        window.addToCartUrl = '{{ route("menu.addToCart") }}';
    </script>
    <script src="{{ asset('js/menu.js') }}"></script>
@endpush