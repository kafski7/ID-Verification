<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Services\QrTokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QrController extends Controller
{
    public function __construct(private readonly QrTokenService $qr) {}

    /**
     * Show the QR code page for a staff member.
     * Generates a token automatically if none exists.
     */
    public function show(Staff $staff): View
    {
        $token = $staff->activeToken();

        if (! $token) {
            $token = $this->qr->generate($staff);
        }

        $url        = $this->qr->buildUrl($token);
        $qrDataUri  = $this->qr->generateQrDataUri($url);

        return view('admin.staff.qr', compact('staff', 'token', 'url', 'qrDataUri'));
    }

    /**
     * Revoke the current token and generate a fresh one.
     */
    public function regenerate(Request $request, Staff $staff): RedirectResponse
    {
        $this->authorizeHrAdmin();

        $this->qr->regenerate($staff, $request->user());

        return redirect()
            ->route('admin.staff.qr.show', $staff)
            ->with('success', 'QR token regenerated successfully.');
    }

    /**
     * Revoke the current token without generating a replacement.
     */
    public function revoke(Request $request, Staff $staff): RedirectResponse
    {
        $this->authorizeHrAdmin();

        $this->qr->revokeAll($staff, $request->user());

        return redirect()
            ->route('admin.staff.qr.show', $staff)
            ->with('success', 'QR token revoked. Generate a new one when ready.');
    }

    private function authorizeHrAdmin(): void
    {
        /** @var \App\Models\AdminUser $user */
        $user = auth()->user();

        abort_unless($user->isHrAdmin(), 403, 'Only HR Admin or Super Admin can manage QR tokens.');
    }
}
