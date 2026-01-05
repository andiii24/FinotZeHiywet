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
            $table->foreignId('group_cat_id')->nullable()->after('user_id')->constrained('group_cats')->nullOnDelete();
            $table->boolean('for_all')->default(false)->after('group_cat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['group_cat_id']);
            $table->dropColumn(['group_cat_id', 'for_all']);
        });
    }
};
