<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
 Schema::create('attendance_sessions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('subject_id')
        ->constrained('subjects')
        ->cascadeOnDelete();

    $table->foreignId('room_id')
        ->constrained('rooms')
        ->restrictOnDelete();

    $table->unsignedInteger('lecture_number');
    $table->string('lecture_title');

    $table->text('qr_current_code')->nullable();
    $table->dateTime('qr_expires_at')->nullable();
$table->dateTime('start_time');
$table->dateTime('end_time')->nullable();
    $table->enum('status', [
        'Active',
        'Ended'
    ])->default('Active');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
