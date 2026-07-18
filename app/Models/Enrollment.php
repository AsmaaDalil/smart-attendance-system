<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Enrollment extends Model
{
    protected $table = 'student_subject';

    protected $fillable = [
        'student_id',
        'subject_id',
    ];

    protected static function booted(): void
    {
        static::saving(function (Enrollment $enrollment): void {
            $alreadyExists = self::query()
                ->where('student_id', $enrollment->student_id)
                ->where('subject_id', $enrollment->subject_id)
                ->when(
                    $enrollment->exists,
                    fn ($query) => $query->whereKeyNot($enrollment->getKey())
                )
                ->exists();

            if ($alreadyExists) {
                throw ValidationException::withMessages([
                    'data.subject_id' =>
                        'This student is already enrolled in this subject.',
                ]);
            }
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}