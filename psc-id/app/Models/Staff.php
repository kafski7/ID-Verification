<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Staff extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'staff_id',
        'id_no',
        'full_name',
        'position',
        'job_grade',
        'department',
        'phone',
        'email',
        'sex',
        'other_contacts',
        'photo_path',
        'date_of_issue',
        'card_expires',
        'status',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'card_expires'  => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Staff $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function qrTokens(): HasMany
    {
        return $this->hasMany(QrToken::class, 'staff_uuid', 'uuid');
    }

    public function activeToken(): ?QrToken
    {
        return $this->qrTokens()
            ->where('revoked', false)
            ->latest('issued_at')
            ->first();
    }

    public function scanLogs(): HasMany
    {
        return $this->hasMany(ScanLog::class, 'staff_uuid', 'uuid');
    }
}
