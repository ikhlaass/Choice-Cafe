@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endpush

@section('content')
<div class="header-checkout">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center" style="gap: 12px;">
            <a href="{{ route('home') }}" class="back-arrow">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1>Checkout</h1>
        </div>

        <div class="table-badge">
            Table {{ str_pad(session('table_number', '01'), 2, '0', STR_PAD_LEFT) }}
            <i class="fas fa-times"></i>
        </div>
    </div>
</div>

<div class="content-wrapper">
    @php $cart = session('cart', []); $subtotal = 0; @endphp
    
    @if(empty($cart))
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h3>Your cart is empty</h3>
            <p>Add some delicious items to get started!</p>
            <a href="{{ route('home') }}" class="payment-btn" style="display: inline-block; text-decoration: none;">Browse Menu</a>
        </div>
    @else
        <div class="item-list">
            @foreach($cart as $id => $item)
                @php $itemTotal = $item['price'] * $item['quantity']; $subtotal += $itemTotal; @endphp
                <div class="item-card" data-item-id="{{ $id }}">
                    <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100' }}" class="item-image" alt="{{ $item['name'] }}">
                    <div class="item-content">
                        <div class="item-header">
                            <div class="item-name" data-item-name="{{ $item['name'] }}">{{ $item['name'] }}</div>
                            <i class="fas fa-pencil edit-icon" onclick="openNotesModal('{{ $id }}')"></i>
                        </div>
                        <div class="item-notes" data-notes-id="{{ $id }}">
                            <strong>Notes:</strong> {{ $item['notes'] ?: 'No notes' }}
                        </div>
                        <div class="item-footer">
                            <div class="item-price" data-price="{{ $item['price'] }}">Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                            <div class="qty-group">
                                <button type="button" class="qty-btn" onclick="updateQuantity('{{ $id }}', -1)">−</button>
                                <span class="qty-value" data-qty-id="{{ $id }}">{{ $item['quantity'] }}</span>
                                <button type="button" class="qty-btn" onclick="updateQuantity('{{ $id }}', 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div class="total-section">
                <span class="total-label">Total</span>
                <span class="total-amount" id="totalAmount">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <form method="POST" action="{{ route('order.store') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="total_amount" id="hiddenTotal" value="{{ $subtotal }}">
                <button type="submit" class="payment-btn">
                    Payment
                </button>
            </form>
        </div>

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay" onclick="closeNotesModal()"></div>

        <!-- Simple Notes Modal -->
        <div class="notes-modal" id="notesModal">
            <div class="notes-modal-header">
                <div>
                    <h3 class="notes-modal-title">Add Notes</h3>
                    <p class="notes-item-name" id="notesItemName"></p>
                </div>
                <button class="close-btn" onclick="closeNotesModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="notes-input-wrapper">
                <textarea 
                    id="notesTextarea" 
                    class="notes-textarea" 
                    placeholder="Add special requests here..."
                    maxlength="200"
                ></textarea>
            </div>

            <div class="notes-actions">
                <button type="button" class="notes-btn notes-btn-cancel" onclick="closeNotesModal()">
                    Cancel
                </button>
                <button type="button" class="notes-btn notes-btn-save" id="saveNotesBtn" onclick="saveNotes()">
                    Save
                </button>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        // Set URL for AJAX calls (Laravel route)
        window.updateQuantityUrl = '{{ route("cart.updateQuantity") }}';
    </script>
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush