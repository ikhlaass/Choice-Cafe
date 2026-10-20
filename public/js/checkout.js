/**
 * ========================================================
 * CHECKOUT.JS - Ultra-Modern Checkout Interactions
 * Choice Cafe Self Order
 * ========================================================
 */

let currentEditingItemId = null;

function openNotesModal(itemId) {
    currentEditingItemId = itemId;
    const notesElement = document.querySelector(`[data-notes-id="${itemId}"]`);
    const itemCard = document.querySelector(`[data-item-id="${itemId}"]`);
    if (!itemCard || !notesElement) return;

    const itemName = itemCard.querySelector('[data-item-name]').textContent;
    let currentNotes = notesElement.textContent.trim();
    if (currentNotes === '+ Tambah catatan' || currentNotes === 'No notes') {
        currentNotes = '';
    }

    document.getElementById('notesItemName').textContent = itemName;
    document.getElementById('notesTextarea').value = currentNotes;

    document.getElementById('modalOverlay').classList.add('show');
    document.getElementById('notesModal').classList.add('show');

    setTimeout(() => {
        document.getElementById('notesTextarea').focus();
    }, 250);
}

function closeNotesModal() {
    const modal = document.getElementById('notesModal');
    const overlay = document.getElementById('modalOverlay');
    if (modal) modal.classList.remove('show');
    setTimeout(() => {
        if (overlay) overlay.classList.remove('show');
    }, 180);
    currentEditingItemId = null;
}

function saveNotes() {
    if (!currentEditingItemId) return;

    const newNotes = document.getElementById('notesTextarea').value.trim();
    const saveBtn = document.getElementById('saveNotesBtn');
    const notesElement = document.querySelector(`[data-notes-id="${currentEditingItemId}"]`);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    saveBtn.disabled = true;
    saveBtn.textContent = 'Menyimpan...';

    fetch(`/cart/update-notes/${currentEditingItemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ notes: newNotes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (notesElement) {
                notesElement.textContent = newNotes ? newNotes : '+ Tambah catatan';
            }
            showToast('Catatan berhasil disimpan!', 'success');
            closeNotesModal();
        } else {
            showToast('Gagal menyimpan catatan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Simpan Catatan';
    });
}

function updateQuantity(itemId, delta) {
    const qtyElement = document.querySelector(`[data-qty-id="${itemId}"]`);
    const itemCard = document.querySelector(`[data-item-id="${itemId}"]`);
    if (!itemCard || !qtyElement) return;

    const itemName = itemCard.querySelector('[data-item-name]').textContent;
    const currentQty = parseInt(qtyElement.textContent);
    const newQty = currentQty + delta;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (newQty <= 0) {
        if (!confirm(`Hapus ${itemName} dari pesanan?`)) return;
    }

    const buttons = itemCard.querySelectorAll('.checkout-step-btn');
    buttons.forEach(btn => btn.disabled = true);

    fetch(window.updateQuantityUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
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
                showToast(`${itemName} dihapus`, 'success');
                itemCard.style.opacity = '0';
                itemCard.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    itemCard.remove();
                    if (document.querySelectorAll('.checkout-food-card').length === 0) {
                        location.reload();
                    } else {
                        updateTotals();
                    }
                }, 250);
            } else {
                qtyElement.textContent = newQty;
                buttons.forEach(btn => btn.disabled = false);
                updateTotals();
            }
        } else {
            showToast('Gagal mengubah jumlah', 'error');
            buttons.forEach(btn => btn.disabled = false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kendala jaringan', 'error');
        buttons.forEach(btn => btn.disabled = false);
    });
}

function updateTotals() {
    let subtotal = 0;

    document.querySelectorAll('.checkout-food-card').forEach(card => {
        const price = parseInt(card.querySelector('.checkout-item-price').dataset.price);
        const qty = parseInt(card.querySelector('.checkout-step-val').textContent);
        subtotal += price * qty;
    });

    const formatted = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
    
    const totalEl = document.getElementById('totalAmount');
    if (totalEl) totalEl.textContent = formatted;

    const subtotalVal = document.getElementById('subtotalVal');
    if (subtotalVal) subtotalVal.textContent = formatted;

    const billingTotalVal = document.getElementById('billingTotalVal');
    if (billingTotalVal) billingTotalVal.textContent = formatted;

    const hiddenTotal = document.getElementById('hiddenTotal');
    if (hiddenTotal) hiddenTotal.value = subtotal;
}

// ESC key to close notes modal
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeNotesModal();
    }
});
