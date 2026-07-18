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
   Schema::create('attendance_records', function (Blueprint $table) {
    $table->id();

    $table->foreignId('student_id')
        ->constrained('students')
        ->cascadeOnDelete();

    $table->foreignId('session_id')
        ->constrained('attendance_sessions')
        ->cascadeOnDelete();

$table->dateTime('scanned_at')->nullable();
    $table->enum('status', [
        'Present',
        'Late',
        'Absent',
        'Excused'
    ])->default('Absent');

    $table->decimal('distance_meters', 8, 2)->nullable();
    $table->boolean('is_dorm_approved')->default(false);

    $table->timestamps();

    $table->unique(['student_id', 'session_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
