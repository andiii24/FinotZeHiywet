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
        Schema::create('planning_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_id')->constrained('plannings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['viewer', 'contributor', 'manager'])->default('viewer');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            $table->unique(['planning_id', 'user_id']);
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planning_users');
    }
};
