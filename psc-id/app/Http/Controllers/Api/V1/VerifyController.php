<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Services\QrTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifyController extends Controller
{
    public function __construct(private readonly QrTokenService $qrTokenService) {}

    public function show(Request $request): JsonResponse
    {
        $rawToken = $request->query('token', '');

        if (empty($rawToken)) {
            return response()->json([
                'valid'  => false,
                'reason' => 'Missing token parameter.',
            ], 400);
        }

        $result   = $this->qrTokenService->verify($rawToken);
        $valid    = $result['valid'];
        $staff    = $result['staff'] ?? null;
        $qrToken  = $result['token'] ?? null;
        $reason   = $result['reason'] ?? '';

        // Map reason to appropriate HTTP status code
        $status = match (true) {
            $valid                                        => 200,
            str_contains($reason, 'Malformed')           => 400,
            str_contains($reason, 'signature')           => 403,
            str_contains($reason, 'not found')           => 404,
            str_contains($reason, 'revoked')             => 410,
            default                                      => 422,
        };

        // Log the scan
        ScanLog::create([
            'token_nonce' => $qrToken?->nonce ?? substr($rawToken, 0, 64),
            'staff_uuid'  => $staff?->uuid,
            'scanned_at'  => now(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'result'      => $valid ? 'VALID' : 'INVALID',
        ]);

        if (! $valid) {
            return response()->json([
                'valid'  => false,
                'reason' => $reason,
            ], $status);
        }

        // Build photo URL only when explicitly requested (opt-in to avoid large payloads)
        $photoUrl = null;
        if ($request->boolean('include_photo') && $staff->photo_path
            && Storage::disk('private')->exists($staff->photo_path)) {
            $bytes    = Storage::disk('private')->get($staff->photo_path);
            $mime     = Storage::disk('private')->mimeType($staff->photo_path) ?: 'image/jpeg';
            $photoUrl = 'data:' . $mime . ';base64,' . base64_encode($bytes);
        }

        return response()->json([
            'valid'     => true,
            'staff'     => [
                'staff_id'       => $staff->staff_id,
                'id_no'          => $staff->id_no,
                'full_name'      => $staff->full_name,
                'sex'            => $staff->sex,
                'position'       => $staff->position,
                'job_grade'      => $staff->job_grade,
                'department'     => $staff->department,
                'status'         => $staff->status,
                'date_of_issue'  => $staff->date_of_issue?->toDateString(),
                'card_expires'   => $staff->card_expires?->toDateString(),
                'other_contacts' => $staff->other_contacts,
                'photo'          => $photoUrl,
            ],
            'scanned_at' => now()->toIso8601String(),
        ], 200);
    }
}
