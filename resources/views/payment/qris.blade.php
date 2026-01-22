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
            <!-- Header -->
            <div class="qris-title">Complete Your Payment</div>
            <div class="qris-subtitle">Secure QRIS payment via Midtrans</div>

            <!-- Transaction Info -->
            <div class="transaction-info">
                <div class="info-row">
                    <span class="info-label">Transaction Code</span>
                    <span class="info-value">{{ $order['transaction_code'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Payment</span>
                    <span class="info-value amount">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Button -->
            <button id="pay-button" class="btn-open-payment">
                <i class="fas fa-qrcode"></i>
                Pay Now
            </button>

            <!-- Payment Steps -->
            <div class="payment-steps">
                <p data-step="1">Click "Pay Now" button above</p>
                <p data-step="2">Scan QR code in the popup</p>
                <p data-step="3">Complete payment with your e-wallet</p>
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

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const snapToken = '{{ $order["snap_token"] ?? "" }}';
    
    if (!snapToken) {
        alert('Payment session expired. Please try again.');
        window.location.href = '{{ route("home") }}';
        return;
    }

    // Pay button click handler
    document.getElementById('pay-button').addEventListener('click', function() {
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

    // Countdown timer
    let timeLeft = 3600;
    const countdownElement = document.getElementById('countdown');
    
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
});
</script>
@endsection
