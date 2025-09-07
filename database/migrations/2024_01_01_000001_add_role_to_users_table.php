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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'instructor', 'student'])->default('student')->after('email');
            $table->string('bio')->nullable()->after('password');
            $table->string('profile_photo')->nullable()->after('bio');
            $table->decimal('total_earnings', 10, 2)->default(0)->after('profile_photo');
            $table->boolean('is_approved')->default(true)->after('total_earnings');
            $table->json('preferences')->nullable()->after('is_approved');
            $table->timestamp('last_login_at')->nullable()->after('preferences');
            
            // Indexes for performance
            $table->index('role');
            $table->index(['role', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['role', 'is_approved']);
            $table->dropColumn([
                'role', 
                'bio', 
                'profile_photo', 
                'total_earnings', 
                'is_approved', 
                'preferences',
                'last_login_at'
            ]);
        });
    }
};