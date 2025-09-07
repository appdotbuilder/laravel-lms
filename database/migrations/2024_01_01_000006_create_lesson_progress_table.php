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
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('course_lessons')->onDelete('cascade');
            $table->boolean('completed')->default(false);
            $table->integer('watch_time_seconds')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Unique constraint - one progress record per enrollment per lesson
            $table->unique(['enrollment_id', 'lesson_id']);
            
            // Indexes for performance
            $table->index(['enrollment_id', 'completed']);
            $table->index('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};