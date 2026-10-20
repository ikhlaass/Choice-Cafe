/**
 * ========================================================
 * MENU.JS - Hyper-Modern Food App Interactions
 * Choice Cafe Self Order
 * ========================================================
 */

function switchTab(category) {
    window.location.href = `?category=${encodeURIComponent(category)}`;
}

function filterMenus() {
    const input = document.getElementById('searchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.food-item-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const nameEl = row.querySelector('.food-name-title');
        const descEl = row.querySelector('.food-desc-text');
        const name = nameEl ? nameEl.textContent.toLowerCase() : '';
        const desc = descEl ? descEl.textContent.toLowerCase() : '';

        if (name.includes(input) || desc.includes(input)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const countEl = document.getElementById('menuCount');
    if (countEl) {
        countEl.textContent = input ? `${visibleCount} Ditemukan` : `${rows.length} Menu`;
    }
}

function addToCart(button, menuId, name, price, image) {
    if (button.disabled) return;

    button.disabled = true;
    button.classList.add('adding');
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i>';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            updateCartDisplay(data.cart_summary);
            showToast(`${name} ditambahkan!`, 'success');

            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.classList.remove('adding');
                button.disabled = false;
            }, 600);
        } else {
            showToast('Gagal menambahkan menu', 'error');
            resetButton(button, originalHtml);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kendala saat menambahkan', 'error');
        resetButton(button, originalHtml);
    });
}

function resetButton(button, originalHtml) {
    button.innerHTML = originalHtml;
    button.classList.remove('adding');
    button.disabled = false;
}

function updateCartDisplay(summary) {
    const stickyCart = document.getElementById('stickyCart');
    const cartText = document.getElementById('cartText');
    const cartTotal = document.getElementById('cartTotal');

    if (summary && summary.count > 0) {
        if (stickyCart) stickyCart.classList.add('show');
        if (cartText) cartText.textContent = `${summary.count} Menu Dipilih`;
        if (cartTotal) cartTotal.textContent = `Rp ${formatRupiah(summary.total)}`;
    } else {
        if (stickyCart) stickyCart.classList.remove('show');
    }
}

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}
