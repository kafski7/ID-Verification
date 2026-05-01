<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrToken extends Model
{
    protected $fillable = [
        'staff_uuid',
        'nonce',
        'issued_at',
        'expires_at',
        'revoked',
        'revoked_at',
        'revoked_by',
    ];

    protected $casts = [
        'issued_at'  => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'revoked'    => 'boolean',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_uuid', 'uuid');
    }

    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'revoked_by');
    }

    public function isValid(): bool
    {
        if ($this->revoked) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}
