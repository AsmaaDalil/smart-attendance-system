<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['subject_name', 'subject_code', 'user_id'];

    public function professor() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sessions() {
        return $this->hasMany(AttendanceSession::class);
    }
public function students()
{
    return $this->belongsToMany(Student::class)
        ->withTimestamps();
}
}
