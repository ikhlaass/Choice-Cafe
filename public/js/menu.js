/**
 * ===================================
 * MENU.JS - Menu Page JavaScript
 * For: menus/index.blade.php
 * ===================================
 */

/**
 * Switch category tab
 * @param {string} category - Category name
 */
function switchTab(category) {
    window.location.href = `?category=${encodeURIComponent(category)}`;
}

/**
 * Filter menus based on search input
 */
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

/**
 * Add item to cart via AJAX
 * @param {HTMLElement} button - Add button element
 * @param {number} menuId - Menu item ID
 * @param {string} name - Menu item name
 * @param {number} price - Menu item price
 * @param {string} image - Menu item image URL
 */
function addToCart(button, menuId, name, price, image) {
    // Prevent double click
    if (button.disabled) return;

    button.disabled = true;
    button.classList.add('adding');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i>';

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // AJAX Request
    fetch(window.addToCartUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
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

                // Show toast notification
                showToast(data.message || `${name} added to cart!`, 'success');

                // Reset button after 800ms
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

/**
 * Reset button state
 * @param {HTMLElement} button - Button element
 * @param {string} originalText - Original button text
 */
function resetButton(button, originalText) {
    button.innerHTML = originalText;
    button.classList.remove('adding');
    button.disabled = false;
}

/**
 * Update cart display without page reload
 * @param {Object} summary - Cart summary object
 */
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
