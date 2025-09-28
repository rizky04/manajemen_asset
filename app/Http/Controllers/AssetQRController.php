<?php
namespace App\Http\Controllers;

use App\Models\Asset;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetQrController extends Controller
{
    public function printAll()
    {
        $assets = Asset::all();

        $pdf = Pdf::loadView('assets.qr-print', compact('assets'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('qr-assets.pdf');
    }
}
