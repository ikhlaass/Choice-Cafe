@extends('layouts.app')

@section('content')
<style>
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
        --green-dark: #45A049;
    }
    
    /* BACK ARROW */
    .back-arrow { 
        position: static;
        left: 16px; 
        top: 20px;
        z-index: 101; 
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: transparent;
        transition: all 0.2s;
    }

    .back-arrow:hover {
        transform: translateX(-2px);
    }

    .back-arrow i {
        font-size: 20px;
        color: #000;
    }

    /* HEADER CHECKOUT */
    .header-checkout { 
        padding: 20px 16px 12px;
        background: #F5F6F1;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .header-checkout h1 {
        font-size: 26px;
        font-weight: 700;
        color: #000;
        margin: 0;
    }

    /* TABLE BADGE */
    .table-badge { 
        background: #ffffff; 
        color: #000; 
        padding: 6px 14px;
        border-radius: 22px;
        font-size: 14px; 
        font-weight: 500; 
        border: 1px solid #E1E2DD;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    .table-badge:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table-badge i {
        font-size: 12px;
        opacity: 0.8;
    }

    /* WRAPPER CONTENT */
    .content-wrapper {
        padding: 16px;
        padding-bottom: 120px;
    }
    
    /* ITEM CARDS */
    .item-card { 
        background: white; 
        border-radius: 22px; 
        padding: 12px 14px 16px;
        margin-bottom: 14px; 
        box-shadow:
            0px 4px 12px rgba(0, 0, 0, 0.05),
            0px 12px 24px rgba(0, 0, 0, 0.06);
        display: flex; 
        gap: 12px;
        border: 1px solid #F0F0F0;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        box-shadow:
            0px 6px 16px rgba(0, 0, 0, 0.08),
            0px 16px 32px rgba(0, 0, 0, 0.08);
    }
    
    .item-image {
        width: 90px;
        height: 90px;
        border-radius: 14px;
        object-fit: cover;
        flex-shrink: 0;
    }
    
    .item-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    
    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 4px;
    }
    
    .item-name {
        font-weight: 600;
        font-size: 15px;
        color: #111827;
        line-height: 1.35;
    }
    
    .edit-icon {
        color: #9CA3AF;
        cursor: pointer;
        font-size: 14px;
        padding: 2px;
        flex-shrink: 0;
        transition: color 0.2s;
    }

    .edit-icon:hover {
        color: var(--green-primary);
    }

    .edit-icon:active {
        transform: scale(0.9);
    }
    
    .item-notes {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 8px;
        line-height: 1.4;
        font-style: normal;
    }
    
    .item-notes strong {
        font-weight: 500;
        font-style: normal;
    }
    
    .item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    
    .item-price {
        font-weight: 700;
        font-size: 15px;
        color: #000;
    }
    
    .qty-group { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        background: #F3F4F6;
        border-radius: 10px;
        padding: 4px 8px;
    }
    
    .qty-btn { 
        width: 28px; 
        height: 28px; 
        border: none;
        border-radius: 8px; 
        background: white; 
        color: #000;
        font-weight: 600;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        transition: all 0.2s;
    }
    
    .qty-btn:hover {
        background: #f9fafb;
    }

    .qty-btn:active {
        transform: scale(0.95);
    }

    .qty-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .qty-value {
        font-weight: 600;
        text-align: center;
        font-size: 15px;
        min-width: 22px;
    }
    
    /* BOTTOM BAR */
    .bottom-bar {
        position: fixed;
        bottom: 15px;
        left: 15px;
        right: 15px;
        background: white;
        padding: 10px 20px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.12);
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 12px;
        border-radius: 100px;
    }
    
    .total-section {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .total-section:active {
        transform: scale(0.98);
    }
    
    .total-label {
        font-size: 16px;
        font-weight: 500;
    }
    
    .total-amount {
        font-size: 16px;
        font-weight: 500;
    }
    
    .total-chevron {
        color: #9CA3AF;
        font-size: 14px;
        transition: transform 0.2s;
    }
    
    .total-chevron.rotated {
        transform: rotate(180deg);
    }
    
    /* PAYMENT BUTTON */
    .payment-btn { 
        background: var(--green-primary); 
        color: white; 
        border: none; 
        padding: 10px 28px; 
        border-radius: 50px; 
        font-weight: 500; 
        font-size: 14px; 
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    
    .payment-btn:hover {
        background: var(--green-dark);
        box-shadow: 0 4px 12px rgba(28, 153, 67, 0.3);
    }
    
    .payment-btn:active {
        transform: scale(0.98);
    }
    
    /* MODAL OVERLAY */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        animation: fadeIn 0.2s ease;
    }
    
    .modal-overlay.show {
        display: block;
    }
    
    /* TOTAL DETAILS MODAL */
    .total-modal {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-radius: 24px 24px 0 0;
        padding: 24px;
        z-index: 2001;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    
    .total-modal.show {
        transform: translateY(0);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .modal-title {
        font-size: 20px;
        font-weight: 700;
        color: #000;
    }
    
    .close-btn {
        font-size: 24px;
        color: #9CA3AF;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .close-btn:hover {
        color: #6B7280;
        background: #F3F4F6;
        border-radius: 50%;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 16px;
        font-size: 16px;
        color: #6B7280;
    }
    
    .total-row:last-child {
        margin-bottom: 0;
        font-weight: 700;
        color: #000;
        font-size: 18px;
        padding-top: 16px;
        border-top: 1px solid #F3F4F6;
    }

    /* EDIT NOTES MODAL - MODERN */
    .notes-modal {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-radius: 24px 24px 0 0;
        padding: 24px;
        z-index: 2002;
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        max-height: 80vh;
    }

    .notes-modal.show {
        transform: translateY(0);
    }

    .notes-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .notes-modal-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    .notes-item-name {
        font-size: 14px;
        color: #6B7280;
        margin-top: 4px;
    }

    .notes-input-wrapper {
        position: relative;
        margin-bottom: 20px;
    }

    .notes-textarea {
        width: 100%;
        min-height: 120px;
        padding: 14px 16px;
        border: 2px solid #E5E7EB;
        border-radius: 16px;
        font-size: 15px;
        font-family: inherit;
        resize: vertical;
        transition: all 0.2s;
        background: #F9FAFB;
    }

    .notes-textarea:focus {
        outline: none;
        border-color: var(--green-primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(28, 153, 67, 0.1);
    }

    .notes-textarea::placeholder {
        color: #9CA3AF;
    }

    .notes-char-count {
        position: absolute;
        bottom: -20px;
        right: 0;
        font-size: 12px;
        color: #9CA3AF;
    }

    .notes-suggestions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .suggestion-chip {
        padding: 6px 12px;
        background: #F3F4F6;
        border: 1px solid #E5E7EB;
        border-radius: 20px;
        font-size: 13px;
        color: #6B7280;
        cursor: pointer;
        transition: all 0.2s;
    }

    .suggestion-chip:hover {
        background: var(--green-primary);
        color: white;
        border-color: var(--green-primary);
    }

    .notes-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .notes-btn {
        flex: 1;
        padding: 14px;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .notes-btn-cancel {
        background: #F3F4F6;
        color: #6B7280;
    }

    .notes-btn-cancel:hover {
        background: #E5E7EB;
    }

    .notes-btn-save {
        background: var(--green-primary);
        color: white;
    }

    .notes-btn-save:hover {
        background: var(--green-dark);
        box-shadow: 0 4px 12px rgba(28, 153, 67, 0.3);
    }

    .notes-btn-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-cart {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-cart i {
        font-size: 64px;
        color: #D1D5DB;
        margin-bottom: 16px;
    }

    .empty-cart h3 {
        color: #6B7280;
        margin-bottom: 8px;
        font-size: 20px;
    }

    .empty-cart p {
        color: #9CA3AF;
        font-size: 14px;
        margin-bottom: 24px;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

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
            <div class="total-section" onclick="toggleTotalModal()">
                <span class="total-label">Total</span>
                <span class="total-amount" id="totalAmount">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                <i class="fas fa-chevron-up total-chevron" id="totalChevron"></i>
            </div>
            <form method="POST" action="{{ route('order.store') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="total_amount" id="hiddenTotal" value="{{ $subtotal }}">
                <button type="submit" class="payment-btn">
                    Pay with QRIS
                </button>
            </form>
        </div>

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay" onclick="closeAllModals()"></div>

        <!-- Total Details Modal -->
        <div class="total-modal" id="totalModal">
            <div class="modal-header">
                <h3 class="modal-title">Order Summary</h3>
                <button class="close-btn" onclick="closeTotalModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="total-row">
                <span>Subtotal</span>
                <span id="modalSubtotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Tax (10%)</span>
                <span id="modalTax">Rp{{ number_format($subtotal * 0.1, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Grand Total</span>
                <span id="modalGrandTotal">Rp{{ number_format($subtotal * 1.1, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Modern Edit Notes Modal -->
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

            <div class="notes-suggestions">
                <div class="suggestion-chip" onclick="addSuggestion('No spicy')">🌶️ No spicy</div>
                <div class="suggestion-chip" onclick="addSuggestion('Extra sauce')">🥫 Extra sauce</div>
                <div class="suggestion-chip" onclick="addSuggestion('Less sugar')">🍬 Less sugar</div>
                <div class="suggestion-chip" onclick="addSuggestion('No ice')">🧊 No ice</div>
            </div>

            <div class="notes-input-wrapper">
                <textarea 
                    id="notesTextarea" 
                    class="notes-textarea" 
                    placeholder="e.g., Extra spicy, no onions, well done..."
                    maxlength="200"
                    oninput="updateCharCount()"
                ></textarea>
                <div class="notes-char-count">
                    <span id="charCount">0</span>/200
                </div>
            </div>

            <div class="notes-actions">
                <button type="button" class="notes-btn notes-btn-cancel" onclick="closeNotesModal()">
                    Cancel
                </button>
                <button type="button" class="notes-btn notes-btn-save" id="saveNotesBtn" onclick="saveNotes()">
                    Save Notes
                </button>
            </div>
        </div>
    @endif
</div>

<script>
    let currentEditingItemId = null;

    // ✅ OPEN MODERN NOTES MODAL
    function openNotesModal(itemId) {
        currentEditingItemId = itemId;
        const notesElement = document.querySelector(`[data-notes-id="${itemId}"]`);
        const itemName = document.querySelector(`[data-item-id="${itemId}"] [data-item-name]`).textContent;
        const currentNotes = notesElement.textContent.replace('Notes: ', '').trim();
        const displayNotes = currentNotes === 'No notes' ? '' : currentNotes;

        // Set modal content
        document.getElementById('notesItemName').textContent = itemName;
        document.getElementById('notesTextarea').value = displayNotes;
        updateCharCount();

        // Show modal
        document.getElementById('modalOverlay').classList.add('show');
        document.getElementById('notesModal').classList.add('show');
        
        // Focus textarea
        setTimeout(() => {
            document.getElementById('notesTextarea').focus();
        }, 300);
    }

    // ✅ CLOSE NOTES MODAL
    function closeNotesModal() {
        document.getElementById('notesModal').classList.remove('show');
        setTimeout(() => {
            document.getElementById('modalOverlay').classList.remove('show');
        }, 200);
        currentEditingItemId = null;
    }

    // ✅ ADD SUGGESTION TO TEXTAREA
    function addSuggestion(text) {
        const textarea = document.getElementById('notesTextarea');
        const currentValue = textarea.value.trim();
        
        if (currentValue) {
            textarea.value = currentValue + ', ' + text;
        } else {
            textarea.value = text;
        }
        
        updateCharCount();
        textarea.focus();
    }

    // ✅ UPDATE CHARACTER COUNT
    function updateCharCount() {
        const textarea = document.getElementById('notesTextarea');
        const count = textarea.value.length;
        document.getElementById('charCount').textContent = count;
    }

    // ✅ SAVE NOTES
    function saveNotes() {
        if (!currentEditingItemId) return;

        const newNotes = document.getElementById('notesTextarea').value.trim();
        const saveBtn = document.getElementById('saveNotesBtn');
        const notesElement = document.querySelector(`[data-notes-id="${currentEditingItemId}"]`);

        // Disable button
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

        fetch(`/cart/update-notes/${currentEditingItemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ notes: newNotes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notesElement.innerHTML = `<strong>Notes:</strong> ${newNotes || 'No notes'}`;
                showToast('Notes saved successfully!', 'success');
                closeNotesModal();
            } else {
                showToast('Failed to save notes', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Notes';
        });
    }

    // ✅ UPDATE QUANTITY
    function updateQuantity(itemId, delta) {
        const qtyElement = document.querySelector(`[data-qty-id="${itemId}"]`);
        const itemCard = document.querySelector(`[data-item-id="${itemId}"]`);
        const itemName = itemCard.querySelector('[data-item-name]').textContent;
        const currentQty = parseInt(qtyElement.textContent);
        const newQty = currentQty + delta;

        if (newQty <= 0) {
            if (!confirm('Remove this item from cart?')) return;
        }

        const buttons = itemCard.querySelectorAll('.qty-btn');
        buttons.forEach(btn => btn.disabled = true);

        fetch('{{ route("cart.updateQuantity") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id: itemId,
                delta: delta
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (newQty <= 0) {
                    showToast(`${itemName} removed from cart`, 'success');
                    itemCard.style.opacity = '0';
                    itemCard.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        itemCard.remove();
                        if (document.querySelectorAll('.item-card').length === 0) {
                            location.reload();
                        } else {
                            updateTotals();
                        }
                    }, 300);
                } else {
                    qtyElement.textContent = newQty;
                    buttons.forEach(btn => btn.disabled = false);
                    updateTotals();
                    showToast('Quantity updated', 'success');
                }
            } else {
                showToast('Failed to update quantity', 'error');
                buttons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
            buttons.forEach(btn => btn.disabled = false);
        });
    }

    function updateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-card').forEach(card => {
            const price = parseInt(card.querySelector('.item-price').dataset.price);
            const qty = parseInt(card.querySelector('.qty-value').textContent);
            subtotal += price * qty;
        });

        const tax = subtotal * 0.1;
        const grandTotal = subtotal * 1.1;

        document.getElementById('totalAmount').textContent = `Rp${formatNumber(subtotal)}`;
        document.getElementById('hiddenTotal').value = subtotal;
        document.getElementById('modalSubtotal').textContent = `Rp${formatNumber(subtotal)}`;
        document.getElementById('modalTax').textContent = `Rp${formatNumber(tax)}`;
        document.getElementById('modalGrandTotal').textContent = `Rp${formatNumber(grandTotal)}`;
    }

    function toggleTotalModal() {
        const modal = document.getElementById('totalModal');
        const overlay = document.getElementById('modalOverlay');
        const chevron = document.getElementById('totalChevron');
        
        modal.classList.toggle('show');
        overlay.classList.toggle('show');
        chevron.classList.toggle('rotated');
    }
    
    function closeTotalModal() {
        const modal = document.getElementById('totalModal');
        const overlay = document.getElementById('modalOverlay');
        const chevron = document.getElementById('totalChevron');
        
        modal.classList.remove('show');
        overlay.classList.remove('show');
        chevron.classList.remove('rotated');
    }

    function closeAllModals() {
        closeTotalModal();
        closeNotesModal();
    }

    function formatNumber(num) {
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
</script>
@endsection