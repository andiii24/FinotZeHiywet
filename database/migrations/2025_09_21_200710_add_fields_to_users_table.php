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
            $table->string('role')->default('user');
            $table->foreignId('group_cat_id')->nullable()->constrained('group_cats')->onDelete('set null');
            $table->string('marital_status')->nullable();
            $table->string('education_background')->nullable();
            $table->boolean('work_status')->default(false);
            $table->string('job_title')->nullable();
            $table->string('work_place')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user');
            $table->foreignId('group_cat_id')->nullable()->constrained('group_cats')->onDelete('set null');
            $table->string('marital_status')->nullable();
            $table->string('education_background')->nullable();
            $table->boolean('work_status')->default(false);
            $table->string('job_title')->nullable();
            $table->string('work_place')->nullable();
        });
    }
};
