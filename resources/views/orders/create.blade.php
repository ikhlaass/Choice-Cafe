@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endpush

@section('content')
<div class="checkout-app-frame">
    <!-- Clean Minimal Header -->
    <header class="checkout-header">
        <a href="{{ route('home') }}" class="btn-back" aria-label="Kembali ke Menu">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h1 class="checkout-title">Pesanan Saya</h1>
        <div class="table-badge">
            <span class="table-dot"></span>
            <span>Meja {{ str_pad(session('table_number', '01'), 2, '0', STR_PAD_LEFT) }}</span>
        </div>
    </header>

    <div class="checkout-container">
        @php 
            $cart = session('cart', []); 
            $subtotal = 0; 
        @endphp
        
        @if(empty($cart))
            <!-- Empty Cart State -->
            <div class="empty-cart-view">
                <div class="empty-icon-circle">
                    <i class="fas fa-bag-shopping"></i>
                </div>
                <h2 class="empty-heading">Pesanan Masih Kosong</h2>
                <p class="empty-subtext">Belum ada menu yang dipilih. Silakan kembali ke menu untuk memilih pesanan Anda.</p>
                <a href="{{ route('home') }}" class="btn-browse-menu">
                    Lihat Daftar Menu
                </a>
            </div>
        @else
            <!-- Items Section -->
            <div class="section-label-row">
                <span class="section-label">Menu Terpilih</span>
                <span class="section-badge">{{ count($cart) }} Item</span>
            </div>

            <div class="cart-items-list">
                @foreach($cart as $id => $item)
                    @php 
                        $itemTotal = $item['price'] * $item['quantity']; 
                        $subtotal += $itemTotal; 
                    @endphp
                    <div class="cart-item-card" data-item-id="{{ $id }}">
                        <img 
                            src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&auto=format&fit=crop&q=80' }}" 
                            class="cart-item-thumb" 
                            alt="{{ $item['name'] }}"
                            onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&auto=format&fit=crop&q=80'"
                        >
                        <div class="cart-item-details">
                            <h3 class="cart-item-name" data-item-name="{{ $item['name'] }}">{{ $item['name'] }}</h3>
                            <span class="cart-item-price" data-price="{{ $item['price'] }}">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </span>

                            <button type="button" class="btn-notes-trigger" onclick="openNotesModal('{{ $id }}')">
                                <i class="fas fa-pen-to-square"></i>
                                <span data-notes-id="{{ $id }}">
                                    {{ !empty($item['notes']) ? $item['notes'] : '+ Tambah catatan' }}
                                </span>
                            </button>
                        </div>

                        <!-- Quantity Stepper -->
                        <div class="stepper-box">
                            <button type="button" class="checkout-step-btn" onclick="updateQuantity('{{ $id }}', -1)" aria-label="Kurangi">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="checkout-step-val" data-qty-id="{{ $id }}">{{ $item['quantity'] }}</span>
                            <button type="button" class="checkout-step-btn" onclick="updateQuantity('{{ $id }}', 1)" aria-label="Tambah">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Method Card -->
            <div class="checkout-card">
                <span class="checkout-card-label">Metode Pembayaran</span>
                <div class="payment-method-row">
                    <div class="payment-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="payment-info">
                        <div class="payment-title">Bayar di Kasir (Tunai / QRIS)</div>
                        <div class="payment-desc">Pesanan langsung dikirim ke sistem dapur cafe</div>
                    </div>
                    <i class="fas fa-check-circle payment-check"></i>
                </div>
            </div>

            <!-- Billing Summary Card -->
            <div class="checkout-card">
                <span class="checkout-card-label">Rincian Pembayaran</span>
                <div class="summary-line">
                    <span>Subtotal Menu</span>
                    <span id="subtotalVal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-line">
                    <span>Biaya Layanan</span>
                    <span class="text-free">Gratis</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-total-line">
                    <span>Total Pembayaran</span>
                    <span class="total-price" id="billingTotalVal">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Floating Checkout Action Bar -->
            <div class="checkout-bottom-bar">
                <div class="checkout-total-info">
                    <span class="checkout-total-label">Total Pesanan</span>
                    <span class="checkout-total-amount" id="totalAmount">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>
                <form method="POST" action="{{ route('order.store') }}" class="checkout-action-form">
                    @csrf
                    <input type="hidden" name="total_amount" id="hiddenTotal" value="{{ $subtotal }}">
                    <button type="submit" class="btn-confirm-order">
                        <span>Pesan Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>

            <!-- Minimalist Notes Modal -->
            <div class="modal-overlay" id="modalOverlay" onclick="closeNotesModal()"></div>

            <div class="notes-modal" id="notesModal">
                <div class="modal-handle"></div>
                <div class="notes-modal-header">
                    <div>
                        <h2 class="notes-modal-title">Catatan Pesanan</h2>
                        <p class="notes-item-name" id="notesItemName"></p>
                    </div>
                    <button type="button" class="btn-close-modal" onclick="closeNotesModal()" aria-label="Tutup">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="quick-tags-row">
                    <span class="quick-tag" onclick="addQuickNote('Pedas Sedang')">Pedas Sedang</span>
                    <span class="quick-tag" onclick="addQuickNote('Tidak Pedas')">Tidak Pedas</span>
                    <span class="quick-tag" onclick="addQuickNote('Pisah Sambal')">Pisah Sambal</span>
                    <span class="quick-tag" onclick="addQuickNote('Sedikit Gula')">Sedikit Gula</span>
                    <span class="quick-tag" onclick="addQuickNote('Bungkus')">Bungkus</span>
                </div>

                <textarea 
                    id="notesTextarea" 
                    class="notes-input" 
                    placeholder="Tulis permintaan khusus untuk koki..."
                    maxlength="200"
                ></textarea>

                <div class="modal-actions-row">
                    <button type="button" class="btn-modal-cancel" onclick="closeNotesModal()">
                        Batal
                    </button>
                    <button type="button" class="btn-modal-save" id="saveNotesBtn" onclick="saveNotes()">
                        Simpan Catatan
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.updateQuantityUrl = '{{ route("cart.updateQuantity") }}';
    </script>
    <script src="{{ asset('js/checkout.js') }}"></script>
    <script>
        function addQuickNote(text) {
            const textarea = document.getElementById('notesTextarea');
            if (!textarea) return;
            if (textarea.value.trim().length > 0) {
                textarea.value += ', ' + text;
            } else {
                textarea.value = text;
            }
            textarea.focus();
        }
    </script>
@endpush