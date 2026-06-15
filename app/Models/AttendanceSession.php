<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = ['subject_id', 'room_id', 'lecture_number', 'lecture_title', 'qr_current_code', 'start_time', 'end_time'];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function attendanceRecords() {
        return $this->hasMany(AttendanceRecord::class, 'session_id');
    }
}
