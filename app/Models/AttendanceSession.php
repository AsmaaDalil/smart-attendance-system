<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    protected $fillable = [
        'subject_id',
        'room_id',
        'lecture_number',
        'lecture_title',
        'qr_current_code',
        'qr_expires_at',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'qr_expires_at' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'session_id'
        );
    }
}