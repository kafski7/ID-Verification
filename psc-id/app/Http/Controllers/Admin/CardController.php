<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Services\QrTokenService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CardController extends Controller
{
    public function __construct(private readonly QrTokenService $qr) {}

    /**
     * Browser-preview: interactive flip card using the exact prototype design.
     */
    public function show(Staff $staff): View
    {
        $token = $staff->activeToken() ?? $this->qr->generate($staff);
        $qrDataUri = $this->qr->generateQrDataUri($this->qr->buildUrl($token));
        $photoDataUri = $this->photoDataUri($staff);

        return view('admin.staff.card', compact('staff', 'qrDataUri', 'photoDataUri'));
    }

    /**
     * PDF download: both sides on one page using dompdf.
     */
    public function pdf(Staff $staff): Response
    {
        $token     = $staff->activeToken() ?? $this->qr->generate($staff);
        $qrDataUri = $this->qr->generateQrDataUri($this->qr->buildUrl($token));

        // Embed all images as base64 data URIs so dompdf doesn't need HTTP access
        $photoDataUri    = $this->photoDataUri($staff);
        $ghanaDataUri    = $this->assetDataUri(public_path('img/card/ghana.png'));
        $pscDataUri      = $this->assetDataUri(public_path('img/card/psc.png'));
        $cardBgDataUri   = $this->assetDataUri(public_path('img/card/card-bg.png'));

        $pdf = Pdf::loadView('admin.staff.card-pdf', compact(
            'staff',
            'qrDataUri',
            'photoDataUri',
            'ghanaDataUri',
            'pscDataUri',
            'cardBgDataUri',
        ))
            ->setPaper([0, 0, 240.0, 384.0], 'portrait') // matches @page in card-pdf.blade.php
            ->setOption(['isRemoteEnabled' => false, 'isHtml5ParserEnabled' => true]);

        $filename = 'psc-id-' . str_replace('/', '-', $staff->staff_id) . '.pdf';

        return $pdf->download($filename);
    }

    // -------------------------------------------------------------------------

    /**
     * Return staff photo as a base64 PNG/JPEG data URI, or null if none.
     */
    private function photoDataUri(Staff $staff): ?string
    {
        if (! $staff->photo_path) {
            return null;
        }

        if (! Storage::disk('private')->exists($staff->photo_path)) {
            return null;
        }

        $contents = Storage::disk('private')->get($staff->photo_path);
        $mime     = Storage::disk('private')->mimeType($staff->photo_path) ?? 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }

    /**
     * Read a public asset file and return as base64 data URI.
     */
    private function assetDataUri(string $absolutePath): string
    {
        $contents = file_get_contents($absolutePath);
        $ext      = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $mime     = match ($ext) {
            'png'  => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            default => 'application/octet-stream',
        };

        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }
}
