<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Choice Self Order</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main CSS (Shared Styles) -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JS (Shared Functions) -->
    <script src="{{ asset('js/main.js') }}"></script>
    
    <!-- Auto-show toast if there's a session message -->
    @if(session('success'))
        <script>showToast("{{ session('success') }}", 'success');</script>
    @endif

    @if(session('error'))
        <script>showToast("{{ session('error') }}", 'error');</script>
    @endif

    <!-- Page-specific Scripts -->
    @stack('scripts')
</body>
</html>
