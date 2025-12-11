@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: #F4F6F1;
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Inter', sans-serif;
        min-height: 100vh;
    }
    
    .success-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .success-card { 
        background: white; 
        border-radius: 32px; 
        padding: 48px 32px 32px; 
        text-align: center; 
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        max-width: 420px;
        width: 100%;
        position: relative;
    }
    
    /* Cloche Icon */
    .cloche-icon {
        margin: 0 auto 24px;
        display: inline-block;
    }
    
    .cloche-svg {
        width: 120px;
        height: 120px;
    }
    
    /* Title Section */
    .success-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 12px;
        line-height: 1.3;
        letter-spacing: -0.02em;
    }
    
    .success-subtitle {
        font-size: 15px;
        color: #6B7280;
        margin-bottom: 28px;
        line-height: 1.6;
    }
    
    /* Estimated Time Badge */
    .estimate-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #dfe8da;
        color: #198c3e;
        padding: 5px 15px;
        border-radius: 24px;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 32px;
    }
    
    .estimate-badge i {
        font-size: 16px;
    }
    
    /* Info Section */
    .info-section {
        background: #F9FAFB;
        padding: 0px 16px;
        border-radius: 16px;
        margin-bottom: 24px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
    }
    
    .info-row:not(:last-child) {
        border-bottom: 1px solid #E5E7EB;
    }
    
    .info-label {
        font-size: 14px;
        color: #6B7280;
        font-weight: 500;
    }
    
    .info-value {
        font-size: 16px;
        color: #111827;
        font-weight: 450;
        letter-spacing: 0.02em;
    }
    
    .info-value.status {
        color: #111827;
    }
    
    /* Back Button */
    .back-btn {
        background: #F3F4F6;
        color: #374151;
        border: none;
        padding: 10px 10px;
        border-radius: 100px;
        font-weight: 500;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .back-btn:hover {
        background: #E5E7EB;
    }
    
    .back-btn:active {
        transform: scale(0.98);
    }

    /* Back Arrow Top Left */
    .back-arrow { 
        position: fixed;
        left: 20px; 
        top: 20px; 
        z-index: 101; 
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        backdrop-filter: blur(10px);
    }
    
    .back-arrow i {
        font-size: 18px;
        color: #374151;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .success-card {
            padding: 40px 24px 24px;
        }
        
        .success-title {
            font-size: 20px;
        }
        
        .cloche-svg {
            width: 100px;
            height: 100px;
        }
    }
</style>

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
@endsection