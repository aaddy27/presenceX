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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff_details')->onDelete('cascade');
            $table->date('date');
            $table->time('punch_in')->nullable();
            $table->time('punch_out')->nullable();
            $table->enum('status', ['Present', 'Absent', 'Late In', 'Early Out', 'Half Day', 'Holiday'])->default('Present');
            $table->decimal('productive_hours', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['staff_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
