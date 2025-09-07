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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->decimal('price_paid', 10, 2);
            $table->enum('status', ['active', 'completed', 'cancelled', 'refunded'])->default('active');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            
            // Unique constraint - one enrollment per student per course
            $table->unique(['student_id', 'course_id']);
            
            // Indexes for performance
            $table->index(['student_id', 'status']);
            $table->index(['course_id', 'status']);
            $table->index('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};