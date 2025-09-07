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
        Schema::table('course_reviews', function (Blueprint $table) {
            $table->boolean('instructor_read')->default(false)->after('approved');
            $table->index(['course_id', 'instructor_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_reviews', function (Blueprint $table) {
            $table->dropIndex(['course_id', 'instructor_read']);
            $table->dropColumn('instructor_read');
        });
    }
};