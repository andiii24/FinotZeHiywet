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
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['user_id']);

            // Change the column to be nullable
            $table->foreignId('user_id')->nullable()->change();

            // Add the foreign key constraint back but with nullOnDelete
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['user_id']);

            // Change the column back to non-nullable
            $table->foreignId('user_id')->nullable(false)->change();

            // Add the foreign key constraint back with cascade delete
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
