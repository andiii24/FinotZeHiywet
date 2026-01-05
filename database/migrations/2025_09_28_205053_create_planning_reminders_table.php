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
        Schema::create('planning_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_id')->constrained('plannings')->onDelete('cascade');
            $table->foreignId('planning_task_id')->nullable()->constrained('planning_tasks')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('reminder_date');
            $table->datetime('reminder_time');
            $table->enum('reminder_type', ['email', 'sms', 'push', 'in_app']);
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->json('recipients')->nullable(); // Array of user IDs
            $table->timestamps();

            $table->index(['reminder_time', 'is_sent']);
            $table->index('reminder_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planning_reminders');
    }
};
