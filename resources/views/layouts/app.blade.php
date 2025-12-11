<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Choice Self Order</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont,
                         "SF Pro Text", "SF Pro Display",
                         "Inter", sans-serif;
            background: #F4F6F1; 
            margin: 0;
            padding: 0;
        }
        
        :root {
            --green-primary: #1c9943;
            --green-light: #D1FAE5;
            --shadow-soft: 0 2px 8px rgba(0,0,0,0.08);
            --border-radius: 16px;
        }

        /* Modern Toast Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background: white;
            color: #111827;
            padding: 14px 20px;
            border-radius: 16px;
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.08),
                0 12px 32px rgba(0, 0, 0, 0.12);
            z-index: 99999;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: calc(100% - 32px);
            min-width: 280px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.06);
        }
        
        .toast-notification.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* Toast Icon Container */
        .toast-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }

        /* Success Toast */
        .toast-notification.success .toast-icon {
            background: var(--green-primary);
            color: white;
        }

        /* Error Toast */
        .toast-notification.error {
            border-color: rgba(239, 68, 68, 0.2);
        }

        .toast-notification.error .toast-icon {
            background: #EF4444;
            color: white;
        }

        /* Toast Message */
        .toast-message {
            flex: 1;
            line-height: 1.4;
            color: #374151;
            font-weight: 500;
        }

        /* Close Button */
        .toast-close {
            width: 24px;
            height: 24px;
            border: none;
            background: #F3F4F6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
            color: #6B7280;
            font-size: 12px;
        }

        .toast-close:hover {
            background: #E5E7EB;
            color: #374151;
        }

        .toast-close:active {
            transform: scale(0.9);
        }

        /* Progress Bar */
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: var(--green-primary);
            border-radius: 0 0 16px 16px;
            animation: progressBar 3s linear;
        }

        .toast-notification.error .toast-progress {
            background: #EF4444;
        }

        @keyframes progressBar {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .toast-notification {
                min-width: unset;
                width: calc(100% - 32px);
            }
        }
    </style>
</head>
<body>
    @yield('content')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Modern Toast Notification Script -->
    <script>
        /**
         * Show modern toast notification
         * @param {string} message - Message to display
         * @param {string} type - 'success' or 'error'
         * @param {number} duration - Duration in milliseconds (default: 3000)
         */
        function showToast(message, type = 'success', duration = 3000) {
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
                <div class="toast-progress"></div>
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
         * Close toast manually
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

        // Auto-show toast if there's a session message
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        // Handle keyboard escape to close toast
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const toast = document.querySelector('.toast-notification');
                if (toast) {
                    closeToast(toast.querySelector('.toast-close'));
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
