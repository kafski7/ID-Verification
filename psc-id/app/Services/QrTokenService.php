<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\QrToken;
use App\Models\Staff;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use RuntimeException;

class QrTokenService
{
    /**
     * Generate a new QR token for the given staff member.
     * Any existing active tokens are NOT revoked here; use regenerate() for that.
     */
    public function generate(Staff $staff): QrToken
    {
        $nonce     = bin2hex(random_bytes(32));   // 64-char hex nonce
        $issuedAt  = now();

        $hmac = hash_hmac(
            'sha256',
            $staff->uuid . '|' . $issuedAt->unix() . '|' . $nonce,
            $this->secret()
        );

        return QrToken::create([
            'staff_uuid' => $staff->uuid,
            'nonce'      => $nonce,
            'issued_at'  => $issuedAt,
            'expires_at' => null,   // no expiry — card_expires drives validity
            'revoked'    => false,
        ]);
    }

    /**
     * Build the signed BASE64URL token string for a QrToken record.
     * Format (before encoding): {staff_uuid}|{issued_at_unix}|{nonce}|{hmac}
     */
    public function buildToken(QrToken $token): string
    {
        $hmac = hash_hmac(
            'sha256',
            $token->staff_uuid . '|' . $token->issued_at->unix() . '|' . $token->nonce,
            $this->secret()
        );

        $payload = $token->staff_uuid . '|' . $token->issued_at->unix() . '|' . $token->nonce . '|' . $hmac;

        return $this->base64urlEncode($payload);
    }

    /**
     * Build the full public verification URL for a QrToken.
     */
    public function buildUrl(QrToken $token): string
    {
        return url('/verify/' . $this->buildToken($token));
    }

    /**
     * Verify a raw BASE64URL token string.
     *
     * Returns an array with keys:
     *   valid    bool
     *   staff    Staff|null
     *   token    QrToken|null
     *   reason   string   (empty when valid)
     */
    public function verify(string $rawToken): array
    {
        $invalid = fn (string $reason) => ['valid' => false, 'staff' => null, 'token' => null, 'reason' => $reason];

        $decoded = $this->base64urlDecode($rawToken);
        if ($decoded === false) {
            return $invalid('Malformed token.');
        }

        $parts = explode('|', $decoded, 4);
        if (count($parts) !== 4) {
            return $invalid('Malformed token.');
        }

        [$staffUuid, $issuedAtUnix, $nonce, $providedHmac] = $parts;

        // Recompute HMAC and compare using constant-time comparison
        $expectedHmac = hash_hmac(
            'sha256',
            $staffUuid . '|' . $issuedAtUnix . '|' . $nonce,
            $this->secret()
        );

        if (! hash_equals($expectedHmac, $providedHmac)) {
            return $invalid('Invalid token signature.');
        }

        // Look up the token by nonce (unique field)
        $token = QrToken::where('nonce', $nonce)
            ->where('staff_uuid', $staffUuid)
            ->with('staff')
            ->first();

        if (! $token) {
            return $invalid('Token not found.');
        }

        if ($token->revoked) {
            return $invalid('Token has been revoked.');
        }

        if ($token->expires_at && $token->expires_at->isPast()) {
            return $invalid('Token has expired.');
        }

        $staff = $token->staff;

        if (! $staff) {
            return $invalid('Staff record not found.');
        }

        if ($staff->status !== 'ACTIVE') {
            return ['valid' => false, 'staff' => $staff, 'token' => $token, 'reason' => 'Staff is inactive.'];
        }

        if ($staff->card_expires && $staff->card_expires->isPast()) {
            return ['valid' => false, 'staff' => $staff, 'token' => $token, 'reason' => 'ID card has expired.'];
        }

        return ['valid' => true, 'staff' => $staff, 'token' => $token, 'reason' => ''];
    }

    /**
     * Regenerate a token for the given staff: revoke all active tokens, create a new one.
     */
    public function regenerate(Staff $staff, AdminUser $revokedBy): QrToken
    {
        $this->revokeAll($staff, $revokedBy);

        return $this->generate($staff);
    }

    /**
     * Revoke all active tokens for the given staff member.
     */
    public function revokeAll(Staff $staff, AdminUser $revokedBy): void
    {
        QrToken::where('staff_uuid', $staff->uuid)
            ->where('revoked', false)
            ->update([
                'revoked'    => true,
                'revoked_at' => now(),
                'revoked_by' => $revokedBy->id,
            ]);
    }

    /**
     * Generate a QR code PNG as a base64-encoded data URI string.
     */
    public function generateQrDataUri(string $url): string
    {
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'eccLevel'        => EccLevel::M,
            'scale'           => 6,
            'outputBase64'    => true,
        ]);

        return (new QRCode($options))->render($url);
    }

    // -------------------------------------------------------------------------

    private function secret(): string
    {
        $secret = config('qr.secret');

        if (empty($secret)) {
            throw new RuntimeException('QR_SECRET is not configured.');
        }

        return $secret;
    }

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64urlDecode(string $data): string|false
    {
        $padded = strtr($data, '-_', '+/');
        $padded = str_pad($padded, strlen($padded) + (4 - strlen($padded) % 4) % 4, '=');
        $decoded = base64_decode($padded, true);

        return $decoded;
    }
}
