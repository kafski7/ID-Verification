<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'token_nonce',
        'staff_uuid',
        'scanned_at',
        'ip_address',
        'user_agent',
        'result',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_uuid', 'uuid');
    }
}
