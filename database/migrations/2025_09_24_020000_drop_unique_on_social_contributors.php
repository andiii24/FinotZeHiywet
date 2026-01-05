<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_contributors', function (Blueprint $table) {
            // Drop the unique constraint to allow multiple contributions per user per contribution
            $table->dropUnique('social_contributors_social_contribution_id_user_id_unique');
            // Replace with a non-unique index for performance
            $table->index(['social_contribution_id', 'user_id'], 'idx_social_contribution_user');
        });
    }

    public function down(): void
    {
        Schema::table('social_contributors', function (Blueprint $table) {
            $table->dropIndex('idx_social_contribution_user');
            $table->unique(['social_contribution_id', 'user_id'], 'social_contributors_social_contribution_id_user_id_unique');
        });
    }
};


