@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/payment_simple.css') }}">
@endpush

@section('content')
<div class="payment-page">
    <!-- Header -->
    <div class="payment-header">
        <a href="{{ route('home') }}" class="back-arrow">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h1>Payment</h1>
        <div class="table-badge">
            Table {{ str_pad(session('table_number', '01'), 2, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <!-- Payment Container -->
    <div class="payment-container">
        <!-- QRIS Section -->
        <div class="qris-section">
            @if(!empty($order['is_demo']))
                <div style="background: #FEF3C7; border: 1px solid #FCD34D; color: #92400E; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; text-align: left; line-height: 1.5;">
                    <div style="font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-info-circle"></i> Mode Simulasi (Midtrans Belum Aktif)
                    </div>
                    <span>API Key Midtrans belum diisi di <code>.env</code>. Anda dapat langsung menguji alur pemesanan dengan menekan tombol <strong>Bayar Sekarang (Simulasi Sukses)</strong> di bawah.</span>
                </div>
            @endif

            <!-- Header -->
            <div class="qris-title">Complete Your Payment</div>
            <div class="qris-subtitle">Secure QRIS payment {{ !empty($order['is_demo']) ? '(Simulation Mode)' : 'via Midtrans' }}</div>

            <!-- Transaction Info -->
            <div class="transaction-info">
                <div class="info-row">
                    <span class="info-label">Transaction Code</span>
                    <span class="info-value">{{ $order['transaction_code'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Table</span>
                    <span class="info-value">Table {{ str_pad(session('table_number', $order['table_number'] ?? '01'), 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Payment</span>
                    <span class="info-value amount">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</span>
                </div>
            </div>

            @if(!empty($order['snap_token']))
                <!-- Midtrans Payment Button -->
                <button id="pay-button" class="btn-open-payment" style="margin-bottom: 12px;">
                    <i class="fas fa-qrcode"></i>
                    Pay Now via Midtrans
                </button>
            @endif

            <!-- Simulation Payment Button -->
            <form action="{{ route('payment.simulate-success') }}" method="POST" style="margin-bottom: 20px;">
                @csrf
                <button type="submit" class="btn-open-payment" style="background: {{ !empty($order['snap_token']) ? '#4B5563' : '#16a34a' }}; border: none; cursor: pointer; width: 100%;">
                    <i class="fas fa-check-circle"></i>
                    {{ !empty($order['snap_token']) ? 'Simulasi Bayar Berhasil (Demo Testing)' : 'Bayar Sekarang (Simulasi Sukses)' }}
                </button>
            </form>

            <!-- Payment Steps -->
            <div class="payment-steps">
                @if(!empty($order['snap_token']))
                    <p data-step="1">Klik "Pay Now via Midtrans" di atas</p>
                    <p data-step="2">Pilih metode pembayaran (QRIS / GoPay / VA)</p>
                    <p data-step="3">Selesaikan transaksi pembayaran</p>
                @else
                    <p data-step="1">Klik tombol "Bayar Sekarang (Simulasi Sukses)" di atas</p>
                    <p data-step="2">Sistem otomatis memvalidasi & mencatat pesanan ke database</p>
                    <p data-step="3">Dialihkan ke halaman status persiapan pesanan</p>
                @endif
            </div>

            <!-- Timer -->
            <div class="payment-timer">
                <i class="fas fa-clock"></i>
                <span id="countdown">60:00</span>
            </div>

            <!-- Status -->
            <div class="status-indicator" id="statusIndicator">
                <div class="spinner"></div>
                <span>Waiting for payment...</span>
            </div>
        </div>
    </div>
</div>

@if(!empty($order['snap_token']))
<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const snapToken = '{{ $order["snap_token"] ?? "" }}';
    const payBtn = document.getElementById('pay-button');
    
    if (payBtn && snapToken) {
        payBtn.addEventListener('click', function() {
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route("payment.success") }}';
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    document.getElementById('statusIndicator').innerHTML = '<div class="spinner"></div><span>Payment pending...</span>';
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Payment failed. Please try again.');
                },
                onClose: function() {
                    console.log('Payment popup closed');
                }
            });
        });
    }
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown timer
    let timeLeft = 3600;
    const countdownElement = document.getElementById('countdown');
    
    if (countdownElement) {
        const timer = setInterval(function() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                alert('Payment session expired');
                window.location.href = '{{ route("home") }}';
            }
            
            timeLeft--;
        }, 1000);
    }
});
</script>
@endsection
