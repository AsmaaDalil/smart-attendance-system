<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excuse extends Model
{
    protected $fillable = ['attendance_record_id', 'reason', 'file_path', 'status'];

    public function attendanceRecord() {
        return $this->belongsTo(AttendanceRecord::class);
    }
}
