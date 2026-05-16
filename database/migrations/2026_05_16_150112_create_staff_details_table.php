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
        Schema::create('staff_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->string('staff_code')->unique();
            $table->string('full_name');
            $table->string('phone');
            $table->string('designation');
            $table->date('joining_date');
            $table->longText('face_embedding'); // Store numeric vector array from Flutter
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('monthly_allowances', 10, 2)->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_details');
    }
};
