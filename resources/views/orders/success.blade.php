@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/success.css') }}">
@endpush

@section('content')
<div class="success-page">
    <a href="{{ route('home') }}" class="back-arrow">
        <i class="fas fa-chevron-left"></i>
    </a>

    <div class="success-wrapper">
        <div class="success-card">
            <!-- Cloche Icon -->
            <div class="cloche-icon">
                <svg class="cloche-svg" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Bell/Cloche Base -->
                    <path d="M30 75 L30 73 C30 50 42 40 60 38 C78 40 90 50 90 73 L90 75 Z" 
                          stroke="#D1D5DB" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                    
                    <!-- Handle -->
                    <circle cx="60" cy="32" r="4" fill="#D1D5DB"/>
                    <path d="M60 32 L60 38" stroke="#D1D5DB" stroke-width="2.5" stroke-linecap="round"/>
                    
                    <!-- Plate -->
                    <ellipse cx="60" cy="75" rx="30" ry="6" fill="#E5E7EB"/>
                    
                    <!-- Clock Icon Inside -->
                    <circle cx="60" cy="58" r="12" stroke="#10B981" stroke-width="2" fill="white"/>
                    <path d="M60 52 L60 58 L64 62" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            
            <!-- Title -->
            <h1 class="success-title">Hang Tight — Your Order's on the Way!</h1>
            <p class="success-subtitle">Thanks for your patience — your order is being processed!</p>
            
            <!-- Estimated Time -->
            <div class="estimate-badge">
                <i class="fas fa-clock"></i>
                <span>Est. {{ $order['estimated_time'] ?? '15 Min' }}</span>
            </div>
            
            <!-- Transaction Info -->
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Transaction Code</span>
                    <span class="info-value">#{{ $order['transaction_code'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value status">{{ ucfirst($order['status'] ?? 'Cooking') }}</span>
                </div>
            </div>
            
            <!-- Back to Menu Button -->
            <a href="{{ route('home') }}" class="back-btn">Back to Menu</a>
        </div>
    </div>
</div>
@endsection