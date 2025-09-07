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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->comment('Type of notification: course_approval, forum_reply, payout_request, etc.');
            $table->string('notifiable_type')->comment('Polymorphic relation type');
            $table->unsignedBigInteger('notifiable_id')->comment('Polymorphic relation id');
            $table->string('title');
            $table->text('message')->nullable();
            $table->json('data')->nullable()->comment('Additional notification data');
            $table->boolean('read')->default(false);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'read']);
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index(['type', 'read']);
            $table->index(['user_id', 'type', 'read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};