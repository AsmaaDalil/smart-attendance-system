<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Excuse extends Model
{
    protected $fillable = [
        'attendance_record_id',
        'reason',
        'file_path',
        'status',
    ];

    protected static function booted(): void
    {
        static::saved(function (Excuse $excuse): void {
            if (! $excuse->wasChanged('status')
                && ! $excuse->wasRecentlyCreated) {
                return;
            }

            if ($excuse->status === 'Approved') {
                $excuse->attendanceRecord()->update([
                    'status' => 'Excused',
                ]);

                return;
            }

            /*
             * إذا كان العذر مقبولًا سابقًا ثم تم رفضه
             * أو إعادته إلى الانتظار، نعيد السجل إلى غائب.
             */
            if (
                $excuse->attendanceRecord?->status === 'Excused'
            ) {
                $excuse->attendanceRecord()->update([
                    'status' => 'Absent',
                ]);
            }
        });
    }

    public function attendanceRecord(): BelongsTo
    {
        return $this->belongsTo(
            AttendanceRecord::class
        );
    }
}