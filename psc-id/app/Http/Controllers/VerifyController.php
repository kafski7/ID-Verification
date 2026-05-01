<?php

namespace App\Http\Controllers;

use App\Models\ScanLog;
use App\Services\QrTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifyController extends Controller
{
    public function __construct(private readonly QrTokenService $qrTokenService) {}

    public function show(Request $request, string $token)
    {
        $result = $this->qrTokenService->verify($token);

        $staff        = $result['staff'] ?? null;
        $qrToken      = $result['token'] ?? null;
        $valid        = $result['valid'];
        $reason       = $result['reason'] ?? '';

        // Log every scan attempt
        ScanLog::create([
            'token_nonce' => $qrToken?->nonce ?? substr($token, 0, 64),
            'staff_uuid'  => $staff?->uuid,
            'scanned_at'  => now(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'result'      => $valid ? 'VALID' : 'INVALID',
        ]);

        // Build staff photo data URI whenever a staff record is found (not just for valid tokens)
        $photoDataUri = null;
        if ($staff?->photo_path && Storage::disk('private')->exists($staff->photo_path)) {
            $bytes    = Storage::disk('private')->get($staff->photo_path);
            $mime     = Storage::disk('private')->mimeType($staff->photo_path) ?: 'image/jpeg';
            $photoDataUri = 'data:' . $mime . ';base64,' . base64_encode($bytes);
        }

        return view('verify.show', compact('valid', 'reason', 'staff', 'photoDataUri'));
    }
}
