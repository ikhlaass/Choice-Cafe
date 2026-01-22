<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator - Choice Coffee</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f5f2;
            padding: 20px;
        }
        .qr-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .qr-header {
            background: white;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .qr-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        .qr-subtitle {
            color: #6b7280;
            font-size: 14px;
        }
        .download-all-btn {
            background: var(--green-primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            transition: all 0.2s;
        }
        .download-all-btn:hover {
            background: #45A049;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(28, 153, 67, 0.3);
        }
        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }
        .table-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            text-align: center;
            transition: all 0.2s;
        }
        .table-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        .table-number {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        .download-btn {
            background: var(--green-primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .download-btn:hover {
            background: #45A049;
        }
        .qr-icon {
            font-size: 32px;
            color: var(--green-primary);
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <!-- Header -->
        <div class="qr-header">
            <div class="qr-title">
                <i class="fas fa-qrcode"></i> QR Code Generator
            </div>
            <div class="qr-subtitle">
                Generate and download QR codes for all tables
            </div>
            
            <a href="{{ route('qr.downloadAll') }}" class="download-all-btn">
                <i class="fas fa-download"></i>
                Download All QR Codes (ZIP)
            </a>
        </div>

        <!-- Table Grid -->
        <div class="table-grid">
            @foreach($tables as $table)
            <div class="table-card">
                <i class="fas fa-qrcode qr-icon"></i>
                <div class="table-number">Table {{ $table->table_number }}</div>
                <a href="{{ route('qr.download', $table->table_number) }}" class="download-btn">
                    <i class="fas fa-download"></i>
                    Download
                </a>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
