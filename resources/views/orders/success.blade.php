@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/success.css') }}">
@endpush

@section('content')
<div class="success-page">
    <div class="success-container">
        <!-- Top Navigation -->
        <header class="success-top-nav">
            <a href="{{ route('home') }}" class="btn-nav-back" aria-label="Kembali ke Menu">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="success-top-title">Status Pesanan</h1>
            <div class="table-pill">
                <span class="table-pill-dot"></span>
                <span>Meja {{ str_pad(session('table_number', $order['table_number'] ?? '01'), 2, '0', STR_PAD_LEFT) }}</span>
            </div>
        </header>

        <!-- Main Unified Receipt Card -->
        <div class="receipt-card">
            <!-- Header with Animated Cloche -->
            <div class="receipt-header">
                <div class="cloche-halo">
                    <svg class="cloche-svg" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Steam Wisps -->
                        <path class="steam-line steam-line-1" d="M33 20 C32 15 36 12 34 8" stroke="#C2611A" stroke-width="2.5" stroke-linecap="round"/>
                        <path class="steam-line steam-line-2" d="M40 18 C39 13 43 10 41 6" stroke="#C2611A" stroke-width="2.5" stroke-linecap="round"/>
                        <path class="steam-line steam-line-3" d="M47 20 C46 15 50 12 48 8" stroke="#C2611A" stroke-width="2.5" stroke-linecap="round"/>
                        
                        <!-- Cloche Handle -->
                        <circle cx="40" cy="27" r="3.5" fill="#C2611A"/>
                        <path d="M40 27 L40 31" stroke="#C2611A" stroke-width="2.5" stroke-linecap="round"/>
                        
                        <!-- Cloche Dome -->
                        <path d="M18 55 C18 38 27 31 40 31 C53 31 62 38 62 55 Z" fill="#FDF4ED" stroke="#C2611A" stroke-width="2.5" stroke-linejoin="round"/>
                        
                        <!-- Plate Base -->
                        <path d="M12 55 L68 55" stroke="#A84E0B" stroke-width="3" stroke-linecap="round"/>
                        <ellipse cx="40" cy="57" rx="30" ry="3.5" fill="#F6D7C2"/>
                        
                        <!-- Sparkles -->
                        <circle cx="21" cy="36" r="1.5" fill="#D97706"/>
                        <circle cx="59" cy="38" r="1.5" fill="#D97706"/>
                    </svg>
                </div>

                <h2 class="receipt-title">Pesanan Sedang Disiapkan!</h2>
                <p class="receipt-subtitle">
                    Dapur Choice Cafe sedang memasak pesanan Anda dengan sepenuh hati.
                </p>
            </div>

            <!-- Integrated Clean Stepper -->
            <div class="stepper-wrap">
                <div class="stepper-track">
                    <div class="stepper-line-bg"></div>
                    <div class="stepper-line-fill"></div>
                    
                    <!-- Step 1: Placed -->
                    <div class="step-col completed">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="step-label">Diterima</span>
                    </div>

                    <!-- Step 2: Cooking -->
                    <div class="step-col active">
                        <div class="step-circle">
                            <i class="fas fa-fire-burner"></i>
                        </div>
                        <span class="step-label">Dimasak</span>
                    </div>

                    <!-- Step 3: Serving -->
                    <div class="step-col pending">
                        <div class="step-circle">
                            <i class="fas fa-bell-concierge"></i>
                        </div>
                        <span class="step-label">Disajikan</span>
                    </div>
                </div>

                <!-- Clean Centered ETA Pill -->
                <div class="eta-pill">
                    <i class="far fa-clock"></i>
                    <span>Estimasi siap <strong>~{{ $order['estimated_time'] ?? '15 Menit' }}</strong></span>
                </div>
            </div>

            <!-- Dotted Divider -->
            <div class="receipt-divider"></div>

            <!-- Key-Value Meta Info -->
            <div class="receipt-info-grid">
                <div class="info-row">
                    <span class="info-label">Kode Transaksi</span>
                    <button type="button" class="btn-code-copy" onclick="copyTransactionCode('{{ $order['transaction_code'] ?? '' }}')">
                        <span>#{{ $order['transaction_code'] ?? 'N/A' }}</span>
                        <i class="far fa-copy"></i>
                    </button>
                </div>

                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="badge-cooking">
                        <i class="fas fa-circle-notch fa-spin"></i> Sedang Dimasak
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Nomor Meja</span>
                    <span class="info-value">Meja {{ str_pad(session('table_number', $order['table_number'] ?? '01'), 2, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Metode Bayar</span>
                    <span class="info-value text-caramel">
                        <i class="fas fa-cash-register me-1"></i> Kasir (Tunai / QRIS)
                    </span>
                </div>
            </div>

            <!-- Ordered Menu Items Section -->
            @if(!empty($order['items']) && is_array($order['items']))
                <div class="receipt-divider"></div>

                <div class="items-section">
                    <div class="items-heading-row">
                        <span class="items-heading-title">Rincian Menu</span>
                        <span class="items-heading-count">{{ count($order['items']) }} Item</span>
                    </div>

                    <div class="items-list">
                        @foreach($order['items'] as $item)
                            <div class="item-line">
                                <div class="item-left">
                                    <span class="item-qty-name">{{ $item['quantity'] ?? 1 }}x {{ $item['name'] ?? 'Menu' }}</span>
                                    @if(!empty($item['notes']))
                                        <span class="item-note">Catatan: {{ $item['notes'] }}</span>
                                    @endif
                                </div>
                                <span class="item-price">
                                    Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Total Payment Box -->
            <div class="receipt-total-box">
                <div class="total-row">
                    <span class="total-label">Total Bayar</span>
                    <span class="total-amount">
                        Rp {{ number_format($order['total_amount'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Single Clean Action Button -->
        <div class="action-btn-wrap">
            <a href="{{ route('home') }}" class="btn-return-menu">
                <i class="fas fa-utensils"></i>
                <span>Kembali ke Menu</span>
            </a>
        </div>
    </div>
</div>

<!-- Copy Toast Notification -->
<div class="copy-toast" id="copyToast">
    <i class="fas fa-check-circle" style="color: #C2611A;"></i>
    <span>Kode transaksi berhasil disalin!</span>
</div>

@push('scripts')
<!-- Canvas Confetti CDN -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fire warm celebration confetti
    if (typeof confetti === 'function') {
        confetti({
            particleCount: 40,
            spread: 55,
            origin: { y: 0.6 },
            colors: ['#C2611A', '#D97706', '#FDBA74', '#A84E0B', '#231815']
        });
    }
});

function copyTransactionCode(code) {
    if (!code) return;
    navigator.clipboard.writeText(code).then(() => {
        showCopyToast();
    }).catch(() => {
        const temp = document.createElement('textarea');
        temp.value = code;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        showCopyToast();
    });
}

function showCopyToast() {
    const toast = document.getElementById('copyToast');
    if (!toast) return;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2200);
}
</script>
@endpush
@endsection