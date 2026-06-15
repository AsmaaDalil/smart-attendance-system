<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'email', 'password', 'phone', 'address', 'is_dormitory', 'academic_year', 'device_token'];

    public function attendanceRecords() {
        return $this->hasMany(AttendanceRecord::class);
    }
}
