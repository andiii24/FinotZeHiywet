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
        Schema::create('planning_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_id')->constrained('plannings')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('priority_level', ['low', 'medium', 'high', 'critical']);
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('not_started');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            $table->json('dependencies')->nullable(); // Array of task IDs this task depends on
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['planning_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('priority_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planning_tasks');
    }
};
