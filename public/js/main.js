/**
 * ===================================
 * MAIN.JS - Shared JavaScript Functions
 * Self-Order App
 * ===================================
 */

/**
 * Format number with thousand separator (Indonesian format)
 * @param {number} num - Number to format
 * @returns {string} Formatted number string
 */
function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Show modern toast notification
 * @param {string} message - Message to display
 * @param {string} type - 'success' or 'error'
 * @param {number} duration - Duration in milliseconds (default: 1000)
 */
function showToast(message, type = 'success', duration = 1000) {
    // Remove existing toast if any
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

    // Create new toast
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;

    // Icon based on type
    const icon = type === 'error'
        ? '<i class="fas fa-times-circle"></i>'
        : '<i class="fas fa-check-circle"></i>';

    // Build toast HTML
    toast.innerHTML = `
        <div class="toast-icon">${icon}</div>
        <div class="toast-message">${message}</div>
        <button class="toast-close" onclick="closeToast(this)">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(toast);

    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // Auto hide toast
    const autoHideTimeout = setTimeout(() => {
        hideToast(toast);
    }, duration);

    // Store timeout ID for manual close
    toast._autoHideTimeout = autoHideTimeout;
}

/**
 * Hide toast with animation
 * @param {HTMLElement} toast - Toast element to hide
 */
function hideToast(toast) {
    toast.classList.remove('show');
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 400);
}

/**
 * Close toast manually (called from close button)
 * @param {HTMLElement} button - Close button element
 */
function closeToast(button) {
    const toast = button.closest('.toast-notification');
    if (toast) {
        // Clear auto-hide timeout
        if (toast._autoHideTimeout) {
            clearTimeout(toast._autoHideTimeout);
        }
        hideToast(toast);
    }
}

// Handle keyboard escape to close toast
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const toast = document.querySelector('.toast-notification');
        if (toast) {
            closeToast(toast.querySelector('.toast-close'));
        }
    }
});
