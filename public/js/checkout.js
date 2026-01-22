/**
 * ===================================
 * CHECKOUT.JS - Checkout Page JavaScript
 * For: orders/create.blade.php
 * ===================================
 */

let currentEditingItemId = null;

/**
 * Open modern notes modal
 * @param {string} itemId - Item ID to edit notes for
 */
function openNotesModal(itemId) {
    currentEditingItemId = itemId;
    const notesElement = document.querySelector(`[data-notes-id="${itemId}"]`);
    const itemName = document.querySelector(`[data-item-id="${itemId}"] [data-item-name]`).textContent;
    const currentNotes = notesElement.textContent.replace('Notes: ', '').trim();
    const displayNotes = currentNotes === 'No notes' ? '' : currentNotes;

    // Set modal content
    document.getElementById('notesItemName').textContent = itemName;
    document.getElementById('notesTextarea').value = displayNotes;

    // Show modal
    document.getElementById('modalOverlay').classList.add('show');
    document.getElementById('notesModal').classList.add('show');

    // Focus textarea
    setTimeout(() => {
        document.getElementById('notesTextarea').focus();
    }, 300);
}

/**
 * Close notes modal
 */
function closeNotesModal() {
    document.getElementById('notesModal').classList.remove('show');
    setTimeout(() => {
        document.getElementById('modalOverlay').classList.remove('show');
    }, 200);
    currentEditingItemId = null;
}

/**
 * Save notes via AJAX
 */
function saveNotes() {
    if (!currentEditingItemId) return;

    const newNotes = document.getElementById('notesTextarea').value.trim();
    const saveBtn = document.getElementById('saveNotesBtn');
    const notesElement = document.querySelector(`[data-notes-id="${currentEditingItemId}"]`);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Disable button
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    fetch(`/cart/update-notes/${currentEditingItemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
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

/**
 * Update item quantity
 * @param {string} itemId - Item ID
 * @param {number} delta - Quantity change (+1 or -1)
 */
function updateQuantity(itemId, delta) {
    const qtyElement = document.querySelector(`[data-qty-id="${itemId}"]`);
    const itemCard = document.querySelector(`[data-item-id="${itemId}"]`);
    const itemName = itemCard.querySelector('[data-item-name]').textContent;
    const currentQty = parseInt(qtyElement.textContent);
    const newQty = currentQty + delta;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (newQty <= 0) {
        if (!confirm('Remove this item from cart?')) return;
    }

    const buttons = itemCard.querySelectorAll('.qty-btn');
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

/**
 * Update totals display
 */
function updateTotals() {
    let subtotal = 0;

    document.querySelectorAll('.item-card').forEach(card => {
        const price = parseInt(card.querySelector('.item-price').dataset.price);
        const qty = parseInt(card.querySelector('.qty-value').textContent);
        subtotal += price * qty;
    });

    document.getElementById('totalAmount').textContent = `Rp${formatNumber(subtotal)}`;
    document.getElementById('hiddenTotal').value = subtotal;
}

// ESC key to close modals
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeNotesModal();
    }
});
