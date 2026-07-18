<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
protected $fillable = [
    'room_name',
    'latitude',
    'longitude',
    'allowed_radius',
];
    public function sessions() {
        return $this->hasMany(AttendanceSession::class);
}
}