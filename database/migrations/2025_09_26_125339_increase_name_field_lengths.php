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
        // Increase name field lengths for all tables that have them
        $tables = [
            'users' => 'name',
            'group_cats' => 'name',
            'skills' => 'name',
            'job_categories' => 'name',
            'job_listings' => 'name',
            'events_categories' => 'name',
            'events' => 'name',
            'social_contribution_categories' => 'name',
            'tasks' => 'name',
        ];

        foreach ($tables as $table => $column) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->string($column, 500)->change();
                });
            }
        }

        // Also increase other string fields that might have similar issues
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'education_background')) {
                    $table->string('education_background', 500)->nullable()->change();
                }
                if (Schema::hasColumn('users', 'job_title')) {
                    $table->string('job_title', 500)->nullable()->change();
                }
                if (Schema::hasColumn('users', 'work_place')) {
                    $table->string('work_place', 500)->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to 255 character limit
        $tables = [
            'users' => 'name',
            'group_cats' => 'name',
            'skills' => 'name',
            'job_categories' => 'name',
            'job_listings' => 'name',
            'events_categories' => 'name',
            'events' => 'name',
            'social_contribution_categories' => 'name',
            'tasks' => 'name',
        ];

        foreach ($tables as $table => $column) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->string($column, 255)->change();
                });
            }
        }

        // Revert other string fields
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'education_background')) {
                    $table->string('education_background', 255)->nullable()->change();
                }
                if (Schema::hasColumn('users', 'job_title')) {
                    $table->string('job_title', 255)->nullable()->change();
                }
                if (Schema::hasColumn('users', 'work_place')) {
                    $table->string('work_place', 255)->nullable()->change();
                }
            });
        }
    }
};
