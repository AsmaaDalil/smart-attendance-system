<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'student_id',
        'session_id',
        'scanned_at',
        'status',
        'distance_meters',
        'is_dorm_approved',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'distance_meters' => 'decimal:2',
        'is_dorm_approved' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function excuse(): HasOne
    {
        return $this->hasOne(Excuse::class);
    }
}