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
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->enum('timeframe_type', ['yearly', 'quarterly', 'monthly']);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('priority_level', ['low', 'medium', 'high', 'critical']);
            $table->foreignId('group_cat_id')->constrained('group_cats')->onDelete('cascade');
            $table->json('group_list')->nullable(); // Array of group IDs
            $table->decimal('budget_amount', 15, 2)->default(0);
            $table->enum('status', ['planning', 'active', 'completed', 'cancelled'])->default('planning');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->index(['timeframe_type', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('priority_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
