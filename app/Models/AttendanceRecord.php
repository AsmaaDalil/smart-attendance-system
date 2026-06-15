<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = ['student_id', 'session_id', 'scanned_at', 'status', 'distance_meters', 'is_dorm_approved'];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function session() {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function excuse() {
        return $this->hasOne(Excuse::class);
    }
}
