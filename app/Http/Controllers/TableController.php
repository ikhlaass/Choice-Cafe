<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;

class TableController extends Controller
{
    /**
     * Handle QR code scan
     */
    public function scan($number)
    {
        // Validate table exists
        $table = Table::where('table_number', $number)->first();
        
        if (!$table) {
            abort(404, 'Table not found');
        }

        // Set table number in session
        session([
            'table_number' => $number,
            'table_id' => $table->id
        ]);

        // Redirect to menu
        return redirect()->route('home');
    }

    /**
     * Show QR generator page
     */
    public function qrGenerator()
    {
        $tables = Table::orderBy('table_number')->get();
        return view('qr.generator', compact('tables'));
    }

    /**
     * Download single QR code
     */
    public function downloadQR($number)
    {
        $table = Table::where('table_number', $number)->firstOrFail();
        
        // Generate QR code URL
        $url = route('table.scan', $number);
        
        // Generate QR code as SVG
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url);

        // Return as download
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="table-' . $number . '.svg"');
    }

    /**
     * Download all QR codes as ZIP
     */
    public function downloadAllQR()
    {
        $tables = Table::all();
        
        // Create temp directory for QR codes
        $tempDir = storage_path('app/temp-qr');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Generate all QR codes
        foreach ($tables as $table) {
            $url = route('table.scan', $table->table_number);
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($url);
            
            file_put_contents(
                $tempDir . '/table-' . $table->table_number . '.svg',
                $qrCode
            );
        }

        // Create ZIP file
        $zipPath = storage_path('app/qr-codes.zip');
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = glob($tempDir . '/*.svg');
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // Clean up temp files
        array_map('unlink', glob($tempDir . '/*.svg'));
        rmdir($tempDir);

        // Download ZIP
        return response()->download($zipPath, 'qr-codes.zip')->deleteFileAfterSend(true);
    }
}
