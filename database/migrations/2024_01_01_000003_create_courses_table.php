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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'pending_approval', 'rejected'])->default('draft');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->integer('duration_minutes')->default(0);
            $table->json('tags')->nullable();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->onDelete('set null');
            $table->integer('max_students')->nullable();
            $table->boolean('featured')->default(false);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_students')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'published_at']);
            $table->index(['instructor_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index('featured');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};